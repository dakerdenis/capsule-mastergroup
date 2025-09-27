<?php

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index', ['title' => 'My Orders']);
    }
}
