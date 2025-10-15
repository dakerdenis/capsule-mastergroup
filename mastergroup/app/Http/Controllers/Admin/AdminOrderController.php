<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->with(['user:id,full_name,email,phone'])
            ->orderByRaw("FIELD(status, 'ordered', 'completed', 'cancelled')")
            ->latest('id')
            ->paginate(20);

        return view('admin.orders.index', [
            'title'  => 'Orders',
            'orders' => $orders,
        ]);
    }

    public function show(Order $order)
    {
        $order->load([
            'user:id,full_name,email,phone,country',
            'items.product' => function ($q) {
                $q->select('id','name','code')
                  ->with([
                      'primaryImage',
                      'images' => fn($qq) => $qq->orderBy('sort_order'),
                  ]);
            },
        ]);

        return view('admin.orders.show', [
            'title' => 'Order #'.$order->number,
            'order' => $order,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:ordered,completed,cancelled',
        ]);

        return DB::transaction(function () use ($order, $data) {
            // блокировка строки заказа
            $order = Order::whereKey($order->getKey())->lockForUpdate()->firstOrFail();
            $old   = $order->status;
            $new   = $data['status'];

            // рефанд при переходе ordered -> cancelled
            if ($old === 'ordered' && $new === 'cancelled') {
                $user = $order->user()->lockForUpdate()->first(); // блокируем пользователя
                if ($user) {
                    $user->increment('cps_total', (int)$order->total_cps);
                    Log::info('Order refund', [
                        'order_id'  => $order->id,
                        'user_id'   => $user->id,
                        'amount'    => (int)$order->total_cps,
                        'from'      => $old,
                        'to'        => $new,
                    ]);
                }
            }

            // выставляем статус и executed_at
            $order->status = $new;

            if ($new === 'completed') {
                if (is_null($order->executed_at)) {
                    $order->executed_at = now();
                }
            } else {
                // для ordered или cancelled — executed_at пустой
                $order->executed_at = null;
            }

            $order->save();

            return back()->with('status', 'Order updated ('.$old.' → '.$order->status.').');
        });
    }
}
