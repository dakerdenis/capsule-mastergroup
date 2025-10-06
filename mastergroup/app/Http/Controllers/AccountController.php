<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function dashboard()
    {
        $randomProducts = Product::query()
            ->with([
                'primaryImage',
                'images' => fn($q) => $q->orderBy('sort_order'),
            ])
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home.dashboard', [
            'title'           => 'Homepage',
            'randomProducts'  => $randomProducts,
        ]);
    }

    public function account() {
        return view('account.dashboard', ['title' => 'Account']);
    }
}
