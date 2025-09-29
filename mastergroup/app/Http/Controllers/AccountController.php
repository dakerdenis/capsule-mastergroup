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
    public function account(){
        return view('account.dashboard', ['title'=>'Account']);
    }
}
