<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login', ['title' => 'Sign in']);
    }

    public function login(LoginRequest $request)
    {
        if (Auth::guard('web')->attempt($request->credentials(), $request->remember())) {
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            // IDE-лайнтфрендли: сначала проверяем интерфейс
            if ($user instanceof MustVerifyEmailContract && ! $user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')
                    ->with('status', 'Please verify your email address.');
            }

            return redirect()->intended(route('account.dashboard'));
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('status', 'You have been signed out.');
    }
}
