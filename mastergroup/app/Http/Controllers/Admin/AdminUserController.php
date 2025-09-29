<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByRaw("FIELD(status, 'pending','approved','rejected')")
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'title' => 'Users',
            'users' => $users,
        ]);
    }

    public function show(User $user)
    {
        return view('admin.users.show', [
            'title' => 'User #'.$user->id,
            'user'  => $user,
        ]);
    }

    public function updateStatus(Request $request, User $user)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'rejected_reason' => 'nullable|string|max:255',
        ]);

        $user->status = $data['status'];

        if ($data['status'] === 'approved') {
            $user->approved_at = now();
            $user->rejected_reason = null;
        } elseif ($data['status'] === 'rejected') {
            $user->rejected_reason = $data['rejected_reason'] ?? null;
            $user->approved_at = null;
        } else { // pending
            $user->approved_at = null;
            // причину оставим как есть либо очистим по желанию:
            // $user->rejected_reason = null;
        }

        $user->save();

        return back()->with('status', 'User status updated.');
    }
}
