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
        return CartItem::where('user_id', $userId)->get(['product_id','qty']);
    }

    private function totalItemsForUser(int $userId): int
    {
        return (int) CartItem::where('user_id', $userId)->sum('qty');
    }

    private function selected(Request $request): array
    {
        return $request->session()->get('cart_selected', []); // ids
    }

    private function putSelected(Request $request, array $ids): void
    {
        $request->session()->put('cart_selected', array_values(array_unique($ids)));
    }

    public function index(Request $request)
    {
        return view('cart.index', [
            'title' => 'My Cart',
            'user_cps' => (int) ($request->user()->cps_total ?? 0),
        ]);
    }

    public function summary(Request $request)
    {
        $uid  = (int) $request->user()->id;
        $rows = $this->itemsForUser($uid);

        $map = [];
        foreach ($rows as $row) {
            $map[(string)$row->product_id] = ['qty' => (int)$row->qty];
        }

        return response()->json([
            'total_items' => $this->totalItemsForUser($uid),
            'cart'        => $map,
            'selected'    => $this->selected($request),
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
        foreach ($rows as $r) $byPid[$r->product_id] = (int) $r->qty;

        $products = Product::query()
            ->with([
                'primaryImage',
                'images' => fn($q) => $q->orderBy('sort_order'),
                'category:id,name',
            ])
            ->whereIn('id', array_keys($byPid))
            ->get();

        $selectedIds = $this->selected($request);
        $items = [];
        $selectedSum = 0;

        foreach ($products as $p) {
            $photoPath = optional($p->primaryImage)->path ?? optional($p->images->first())->path;
            if ($photoPath) {
                $img = Str::startsWith($photoPath, ['http://','https://'])
                    ? $photoPath
                    : asset('storage/' . ltrim($photoPath, '/'));
            } else {
                $img = asset('images/catalog/catalog_placeholder.png');
            }

            $qty = $byPid[$p->id] ?? 0;
            $price = (float) $p->price;
            $isSelected = in_array($p->id, $selectedIds, true);

            if ($isSelected) $selectedSum += $price * $qty;

            $items[] = [
                'id'       => $p->id,
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
            'selected_sum' => (float) $selectedSum,
            'user_cps'     => (int) ($request->user()->cps_total ?? 0),
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
        ]);

        $uid = (int) $request->user()->id;
        $pid = (int) $data['product_id'];

        $item = CartItem::firstOrNew(['user_id' => $uid, 'product_id' => $pid]);
        $item->qty = $this->clampQty(($item->qty ?? 0) + 1);
        $item->qty === 0 ? $item->delete() : $item->save();

        return response()->json([
            'product_id'  => $pid,
            'qty'         => (int)($item->qty ?? 0),
            'total_items' => $this->totalItemsForUser($uid),
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
            'qty'         => (int)($item->qty ?? 0),
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
        $qty = $this->clampQty((int)$data['qty']);

        if ($qty === 0) {
            CartItem::where('user_id',$uid)->where('product_id',$pid)->delete();
        } else {
            CartItem::updateOrCreate(
                ['user_id'=>$uid,'product_id'=>$pid],
                ['qty'=>$qty]
            );
        }

        return response()->json([
            'product_id'  => $pid,
            'qty'         => $qty,
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

        $sel = $this->selected($request);
        if (($k = array_search($pid, $sel, true)) !== false) {
            unset($sel[$k]);
            $this->putSelected($request, $sel);
        }

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

        $sel = $this->selected($request);
        $pid = (int) $data['product_id'];

        if ($data['selected']) {
            $sel[] = $pid;
        } else {
            if (($k = array_search($pid, $sel, true)) !== false) unset($sel[$k]);
        }
        $this->putSelected($request, $sel);

        return response()->json([
            'product_id' => $pid,
            'selected'   => (bool) $data['selected'],
            'selected_ids' => array_values(array_unique($sel)),
        ]);
    }
}
