<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    // Порог и окно блокировки
    private int $maxAttempts = 5;          // попыток
    private int $decaySeconds = 3600;      // 1 час

    public function showLogin()
    {
        // если уже вошёл — уводим в админку
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login', ['title' => 'Admin Sign in']);
    }

    public function login(Request $request)
    {
        // Ключи для лимитера по IP и по паре email+IP
        $ip = $request->ip();
        $email = (string) $request->input('email');
        $keyIp = 'admin_login:ip:' . md5($ip);
        $keyCombo = 'admin_login:combo:' . md5(Str::lower($email) . '|' . $ip);

        // Если превысили порог — шлём 429 с временем ожидания
        if (
            RateLimiter::tooManyAttempts($keyIp, $this->maxAttempts) ||
            RateLimiter::tooManyAttempts($keyCombo, $this->maxAttempts)
        ) {

            $seconds = max(
                RateLimiter::availableIn($keyIp),
                RateLimiter::availableIn($keyCombo)
            );

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', ['seconds' => $seconds]),
            ])->status(429);
        }

        // Валидация входных (DNS-проверку включаем только в проде)
        $rules = [
            'email' => ['required', 'string', 'lowercase', app()->isProduction() ? 'email:rfc,dns' : 'email:rfc'],
            'password' => ['required', 'string', 'min:8', 'max:128'],
        ];

        $validated = $request->validate($rules);

        // Попытка входа
        $ok = Auth::guard('admin')->attempt(
            ['email' => $validated['email'], 'password' => $validated['password']],
            false // remember выключен для админки по безопасности
        );

        if (! $ok) {
            // фиксируем неудачу на два ключа сразу
            RateLimiter::hit($keyIp, $this->decaySeconds);
            RateLimiter::hit($keyCombo, $this->decaySeconds);

            // унифицированная ошибка без утечки, существует ли email
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ])->status(422);
        }

        // успех: очищаем счётчики
        RateLimiter::clear($keyIp);
        RateLimiter::clear($keyCombo);

        // защита от фиксации сессии
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
