<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthActivityLog;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = AuthActivityLog::query()
            ->with(['user:id,full_name,email'])
            ->latest('created_at');

        if ($e = $request->string('event')->toString()) {
            $query->where('event', $e);
        }
        if ($g = $request->string('guard')->toString()) {
            $query->where('guard', $g);
        }
        if ($email = $request->string('email')->toString()) {
            $query->where(function($q) use ($email){
                $q->where('email', 'like', "%{$email}%")
                  ->orWhereHas('user', fn($u)=>$u->where('email','like',"%{$email}%"));
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.dashboard', [
            'title' => 'Admin Dashboard',
            'logs'  => $logs,
        ]);
    }
}
