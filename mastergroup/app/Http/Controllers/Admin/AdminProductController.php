<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index', ['title' => 'Products']);
    }
}
