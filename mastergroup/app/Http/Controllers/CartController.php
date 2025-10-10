<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function clampQty(int $qty): int
    {
        return max(0, min(10, $qty));
    }

    private function itemsForUser(int $userId)
    {
        return CartItem::where('user_id', $userId)->get(['product_id','qty','selected']);
    }

    private function totalItemsForUser(int $userId): int
    {
        return (int) CartItem::where('user_id', $userId)->sum('qty');
    }

    public function index(Request $request)
    {
        return view('cart.index', [
            'title'    => 'My Cart',
            'user_cps' => (int) ($request->user()->cps_total ?? 0),
        ]);
    }

    public function summary(Request $request)
    {
        $uid  = (int) $request->user()->id;
        $rows = $this->itemsForUser($uid);

        $map = [];
        $selected = [];
        foreach ($rows as $row) {
            $map[(string)$row->product_id] = ['qty' => (int)$row->qty];
            if ($row->selected) $selected[] = (int)$row->product_id;
        }

        return response()->json([
            'total_items' => $this->totalItemsForUser($uid),
            'cart'        => $map,
            'selected'    => $selected,
        ]);
    }

    public function items(Request $request)
    {
        $uid  = (int) $request->user()->id;
        $rows = $this->itemsForUser($uid);

        if ($rows->isEmpty()) {
            return response()->json([
                'items'        => [],
                'total_items'  => 0,
                'selected_ids' => [],
                'selected_sum' => 0,
                'user_cps'     => (int) ($request->user()->cps_total ?? 0),
            ]);
        }

        $byPid = [];
        $selectedIds = [];
        foreach ($rows as $r) {
            $byPid[$r->product_id] = (int) $r->qty;
            if ($r->selected) $selectedIds[] = (int) $r->product_id;
        }

        $products = Product::query()
            ->with([
                'primaryImage',
                'images' => fn($q) => $q->orderBy('sort_order'),
                'category:id,name',
            ])
            ->whereIn('id', array_keys($byPid))
            ->get();

        $items = [];
        $selectedSum = 0;

        foreach ($products as $p) {
            $photoPath = optional($p->primaryImage)->path ?? optional($p->images->first())->path;
            $img = $photoPath
                ? (Str::startsWith($photoPath, ['http://','https://']) ? $photoPath : asset('storage/'.ltrim($photoPath, '/')))
                : asset('images/catalog/catalog_placeholder.png');

            $qty   = $byPid[$p->id] ?? 0;
            $price = (int) $p->price; // CPS integer
            $isSelected = in_array($p->id, $selectedIds, true);

            if ($isSelected) $selectedSum += $price * $qty;

            $items[] = [
                'id'       => (int) $p->id,
                'name'     => (string) $p->name,
                'code'     => (string) $p->code,
                'type'     => (string) ($p->type ?? ''),
                'price'    => $price,
                'qty'      => (int) $qty,
                'image'    => $img,
                'selected' => $isSelected,
            ];
        }

        return response()->json([
            'items'        => $items,
            'total_items'  => $this->totalItemsForUser($uid),
            'selected_ids' => $selectedIds,
            'selected_sum' => $selectedSum,
            'user_cps'     => (int) ($request->user()->cps_total ?? 0),
        ]);
    }

    // Лимит: максимум 10 штук по всей корзине и максимум 10 на позицию
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
        ]);

        $uid = (int) $request->user()->id;
        $pid = (int) $data['product_id'];

        $total = (int) CartItem::where('user_id', $uid)->sum('qty');
        if ($total >= 10) {
            $currentQty = (int) (CartItem::where('user_id', $uid)->where('product_id', $pid)->value('qty') ?? 0);
            return response()->json([
                'product_id'  => $pid,
                'qty'         => $currentQty,
                'total_items' => $total,
            ]);
        }

        $item = CartItem::firstOrNew(['user_id' => $uid, 'product_id' => $pid]);
        $newQty = (int) ($item->qty ?? 0) + 1;
        if ($newQty > 10) $newQty = 10;

        $remaining = 10 - $total;
        if ($remaining < 1) {
            $newQty = (int) ($item->qty ?? 0);
        } else {
            $newQty = min($newQty, (int) ($item->qty ?? 0) + $remaining);
        }

        if ($newQty <= 0) {
            $item->delete();
        } else {
            $item->qty = $newQty;
            $item->save();
        }

        $totalAfter = (int) CartItem::where('user_id', $uid)->sum('qty');

        return response()->json([
            'product_id'  => $pid,
            'qty'         => (int) ($item->qty ?? 0),
            'total_items' => $totalAfter,
        ]);
    }

    public function decrement(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
        ]);

        $uid = (int) $request->user()->id;
        $pid = (int) $data['product_id'];

        $item = CartItem::where('user_id',$uid)->where('product_id',$pid)->first();
        if ($item) {
            $item->qty = $this->clampQty($item->qty - 1);
            $item->qty === 0 ? $item->delete() : $item->save();
        }

        return response()->json([
            'product_id'  => $pid,
            'qty'         => (int) ($item->qty ?? 0),
            'total_items' => $this->totalItemsForUser($uid),
        ]);
    }

    public function setQuantity(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'qty'        => ['required','integer','min:0','max:10'],
        ]);

        $uid = (int) $request->user()->id;
        $pid = (int) $data['product_id'];
        $qty = $this->clampQty((int) $data['qty']);

        if ($qty === 0) {
            CartItem::where('user_id',$uid)->where('product_id',$pid)->delete();
        } else {
            // общий лимит 10 по корзине
            $totalOther = (int) CartItem::where('user_id',$uid)->where('product_id','!=',$pid)->sum('qty');
            $qty = min($qty, max(0, 10 - $totalOther));
            if ($qty === 0) {
                CartItem::where('user_id',$uid)->where('product_id',$pid)->delete();
            } else {
                CartItem::updateOrCreate(['user_id'=>$uid,'product_id'=>$pid], ['qty'=>$qty]);
            }
        }

        return response()->json([
            'product_id'  => $pid,
            'qty'         => (int) (CartItem::where('user_id',$uid)->where('product_id',$pid)->value('qty') ?? 0),
            'total_items' => $this->totalItemsForUser($uid),
        ]);
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
        ]);

        $uid = (int) $request->user()->id;
        $pid = (int) $data['product_id'];

        CartItem::where('user_id',$uid)->where('product_id',$pid)->delete();

        return response()->json([
            'product_id'  => $pid,
            'qty'         => 0,
            'total_items' => $this->totalItemsForUser($uid),
        ]);
    }

    public function select(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'selected'   => ['required','boolean'],
        ]);

        $uid = (int) $request->user()->id;
        $pid = (int) $data['product_id'];

        CartItem::where('user_id',$uid)->where('product_id',$pid)->update([
            'selected' => (bool) $data['selected'],
        ]);

        $selectedIds = CartItem::where('user_id',$uid)->where('selected',true)->pluck('product_id')->map(fn($v)=>(int)$v)->all();

        return response()->json([
            'product_id'   => $pid,
            'selected'     => (bool) $data['selected'],
            'selected_ids' => $selectedIds,
        ]);
    }
}
