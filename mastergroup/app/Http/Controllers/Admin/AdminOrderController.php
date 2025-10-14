<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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

// app/Http/Controllers/Admin/AdminOrderController.php

public function show(Order $order)
{
    $order->load([
        'user:id,full_name,email,phone,country',
        // подгружаем сразу primaryImage и images, чтобы избежать N+1
        'items.product' => function ($q) {
            $q->select('id','name','code')  // нужны минимум эти поля
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

        $old = $order->status;
        $order->status = $data['status'];

        // executed_at ставим при completed, убираем иначе
        if ($data['status'] === 'completed' && is_null($order->executed_at)) {
            $order->executed_at = now();
        }
        if ($data['status'] !== 'completed') {
            $order->executed_at = null;
        }

        $order->save();

        return back()->with('status', 'Order updated ('.$old.' → '.$order->status.').');
    }
}
