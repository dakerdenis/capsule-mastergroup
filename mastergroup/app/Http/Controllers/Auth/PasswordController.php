<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function forgot()
    {
        return view('auth.passwords.forgot', [
            'title' => 'Forgot password',
        ]);
    }
    public function sendLink(Request $request)
    {
        // TODO: добавить валидацию и Password::sendResetLink($request->only('email'))
        // временная заглушка, чтобы endpoint был живым:
        return back()->with('status', 'Password reset link is not implemented yet.');
    }
    public function reset(Request $request, string $token)
    {
        return view('auth.passwords.reset', [
            'title' => 'Reset password',
            'token' => $token,
            'email' => $request->query('email'), // может быть null — это нормально
        ]);
    }
    public function update(Request $request)
    {
        // TODO: валидация token/email/password и Password::reset(...)
        return back()->with('status', 'Password reset is not implemented yet.');
    }
}
