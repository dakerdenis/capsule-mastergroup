<?php

// app/Http/Controllers/AccountController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function dashboard()
    {
        return view('account.dashboard', ['title' => 'Homepage']);
    }
}
