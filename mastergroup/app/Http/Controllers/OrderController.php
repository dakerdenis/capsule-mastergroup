<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->withCount(['items as total_qty' => function($q){ $q->select(DB::raw('coalesce(sum(qty),0)')); }])
            ->latest('id')
            ->get();

        // короткое поле «Products» в таблице
        $orders->transform(function($o){
            $o->products_summary = $o->items()
                ->with('product:id,name')
                ->get()
                ->map(fn($it) => $it->product?->name)
                ->filter()
                ->values()
                ->take(3)
                ->implode(', ');
            if ($o->items()->count() > 3) $o->products_summary .= '…';
            return $o;
        });

        return view('orders.index', [
            'title'  => 'My Orders',
            'orders' => $orders,
        ]);
    }

    // JSON детали заказа для модалки
    public function showJson(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load(['items.product' => function($q){
            $q->with(['primaryImage','images' => fn($qq)=>$qq->orderBy('sort_order')]);
        }]);

        $items = [];
        foreach ($order->items as $it) {
            $p = $it->product;
            $photoPath = optional($p->primaryImage)->path ?? optional($p->images->first())->path;
            if ($photoPath) {
                $img = Str::startsWith($photoPath, ['http://','https://']) ? $photoPath : asset('storage/'.ltrim($photoPath,'/'));
            } else {
                $img = asset('images/catalog/catalog_placeholder.png');
            }

            $items[] = [
                'name'  => (string) $p->name,
                'code'  => (string) $p->code,
                'type'  => (string) ($p->type ?? ''),
                'image' => $img,
                'qty'   => (int) $it->qty,
                'price' => (int) $it->price_cps,
                'sum'   => (int) ($it->qty * $it->price_cps),
            ];
        }

        return response()->json([
            'id'           => $order->id,
            'number'       => $order->number,
            'status'       => strtoupper($order->status),
            'total_cps'    => (int) $order->total_cps,
            'created_at'   => $order->created_at?->format('m/d/y H:i'),
            'executed_at'  => $order->executed_at?->format('m/d/y H:i'),
            'items'        => $items,
        ]);
    }

    // POST /orders/place — уже вызывалось с корзины
    public function place(Request $request)
    {
        $user = $request->user();
        $uid  = (int) $user->id;

        return DB::transaction(function () use ($uid, $user) {
            $items = CartItem::query()
                ->where('user_id', $uid)
                ->where('selected', true)
                ->lockForUpdate()
                ->get(['product_id','qty']);

            if ($items->isEmpty()) {
                return response()->json(['ok'=>false,'error'=>'NO_SELECTED_ITEMS','message'=>'No selected items.'], 422);
            }

            $productIds = $items->pluck('product_id')->all();
            $products   = Product::query()
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get(['id','price']);

            $priceById = $products->keyBy('id')->map(fn($p)=>(int)$p->price);

            $total = 0; $prepared = [];
            foreach ($items as $it) {
                $pid=(int)$it->product_id; $qty=(int)$it->qty; $price=(int)($priceById[$pid]??0);
                if ($qty<=0 || $price<=0) continue;
                $total += $qty*$price;
                $prepared[] = ['product_id'=>$pid,'qty'=>$qty,'price_cps'=>$price];
            }

            if ($total<=0 || empty($prepared)) {
                return response()->json(['ok'=>false,'error'=>'INVALID_TOTAL','message'=>'Invalid items total.'],422);
            }

            $currentCps = (int)($user->cps_total ?? 0);
            if ($currentCps < $total) {
                return response()->json(['ok'=>false,'error'=>'NOT_ENOUGH_CPS','message'=>'Not enough CPS.','need'=>$total,'have'=>$currentCps],422);
            }

            $number = $this->generateNumber();

            $order = Order::create([
                'user_id'   => $uid,
                'number'    => $number,
                'total_cps' => $total,
                'status'    => 'ordered',
            ]);

            foreach ($prepared as $row) {
                OrderItem::create(['order_id'=>$order->id] + $row);
            }

            $user->decrement('cps_total', $total);
            CartItem::where('user_id',$uid)->where('selected',true)->delete();

            return response()->json([
                'ok'           => true,
                'order_id'     => $order->id,
                'order_number' => $order->number,
                'total_cps'    => (int)$order->total_cps,
                'new_cps'      => (int)($user->fresh()->cps_total),
            ]);
        });
    }

    private function generateNumber(): string
    {
        do {
            $num = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::where('number', $num)->exists());

        return $num;
    }
}
