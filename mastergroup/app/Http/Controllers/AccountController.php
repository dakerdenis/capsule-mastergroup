<?php

// app/Http/Controllers/AccountController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function dashboard()
    {
        return view('home.dashboard', ['title' => 'Homepage']);
    }
    public function account() {
        // $bonuses = Bonus::where('user_id', auth()->id())->latest()->paginate(10);
        return view('account.dashboard', [
            'title' => 'Account',
            // 'bonuses' => $bonuses,
        ]);
    }
    
}
