<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Code; // ← добавь
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

    public function account(Request $request)
    {
        $user = $request->user();
        $per  = (int) $request->get('per_page', 10);
        $per  = $per > 0 && $per <= 50 ? $per : 10;

        $codes = Code::query()
            ->where('activated_by_user_id', $user->id)
            ->where('status', 'activated')
            ->orderByDesc('activated_at')
            ->orderByDesc('id')
            ->paginate($per)
            ->withQueryString();

        return view('account.dashboard', [
            'title' => 'Account',
            'codes' => $codes, // ← передаем в Blade
        ]);
    }
}
