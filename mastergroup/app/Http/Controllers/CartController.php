<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    private function cart(Request $request): array
    {
        return $request->session()->get('cart', []);
    }

    private function putCart(Request $request, array $cart): void
    {
        $request->session()->put('cart', $cart);
    }

    private function clampQty(int $qty): int
    {
        if ($qty < 0) return 0;
        if ($qty > 10) return 10;
        return $qty;
    }

    private function totalItems(array $cart): int
    {
        return array_sum(array_column($cart, 'qty'));
    }

    public function index(Request $request)
    {
        return view('cart.index', [
            'title' => 'My Cart',
            'cart'  => $this->cart($request),
        ]);
    }

    public function summary(Request $request)
    {
        $cart = $this->cart($request);
        return response()->json([
            'total_items' => $this->totalItems($cart),
            'cart'        => $cart,
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','string','max:100'],
        ]);

        $cart = $this->cart($request);
        $pid  = $data['product_id'];

        $current = $cart[$pid]['qty'] ?? 0;
        $newQty  = $this->clampQty($current + 1);

        $cart[$pid] = [
            'qty' => $newQty,
        ];

        $this->putCart($request, $cart);

        return response()->json([
            'product_id'  => $pid,
            'qty'         => $cart[$pid]['qty'],
            'total_items' => $this->totalItems($cart),
        ]);
    }

    public function decrement(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','string','max:100'],
        ]);

        $cart = $this->cart($request);
        $pid  = $data['product_id'];

        if (!isset($cart[$pid])) {
            return response()->json([
                'product_id'  => $pid,
                'qty'         => 0,
                'total_items' => $this->totalItems($cart),
            ]);
        }

        $newQty = $this->clampQty($cart[$pid]['qty'] - 1);

        if ($newQty === 0) {
            unset($cart[$pid]);
        } else {
            $cart[$pid]['qty'] = $newQty;
        }

        $this->putCart($request, $cart);

        return response()->json([
            'product_id'  => $pid,
            'qty'         => $cart[$pid]['qty'] ?? 0,
            'total_items' => $this->totalItems($cart),
        ]);
    }

    public function setQuantity(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','string','max:100'],
            'qty'        => ['required','integer','min:0','max:10'],
        ]);

        $cart = $this->cart($request);
        $pid  = $data['product_id'];
        $qty  = $this->clampQty((int)$data['qty']);

        if ($qty === 0) {
            unset($cart[$pid]);
        } else {
            $cart[$pid] = ['qty' => $qty];
        }

        $this->putCart($request, $cart);

        return response()->json([
            'product_id'  => $pid,
            'qty'         => $cart[$pid]['qty'] ?? 0,
            'total_items' => $this->totalItems($cart),
        ]);
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','string','max:100'],
        ]);

        $cart = $this->cart($request);
        $pid  = $data['product_id'];

        unset($cart[$pid]);
        $this->putCart($request, $cart);

        return response()->json([
            'product_id'  => $pid,
            'qty'         => 0,
            'total_items' => $this->totalItems($cart),
        ]);
    }
}
