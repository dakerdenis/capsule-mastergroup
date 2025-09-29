<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminOrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index', ['title' => 'Orders']);
    }
}
