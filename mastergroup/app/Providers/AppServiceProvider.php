<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $ip = $request->ip();
            $email = (string) $request->input('email');
            return [
                Limit::perMinute(5)->by('ip:'.sha1($ip)),
                Limit::perMinute(5)->by('combo:'.sha1(Str::lower($email).'|'.$ip)),
            ];
        });

        RateLimiter::for('password-email', function (Request $request) {
            return [ Limit::perMinute(3)->by('ip:'.sha1($request->ip())) ];
        });
    }
}
