<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminCategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', ['title' => 'Categories']);
    }
}
