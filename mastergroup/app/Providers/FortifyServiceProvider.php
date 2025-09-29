<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot()
    {
        // лимитер для USER-логина (используется в middleware 'throttle:login')
        RateLimiter::for('login', function (Request $request) {
            $ip = $request->ip();
            $email = (string) $request->input('email');

            return [
                Limit::perMinute(5)->by('ip:'.sha1($ip)),
                Limit::perMinute(5)->by('combo:'.sha1(Str::lower($email).'|'.$ip)),
            ];
        });

        // лимитер для отправки письма восстановления (используется 'throttle:password-email')
        RateLimiter::for('password-email', function (Request $request) {
            return [ Limit::perMinute(3)->by('ip:'.sha1($request->ip())) ];
        });

        // твои бины Fortify (оставляем как было)
        $this->app->singleton(CreateNewUser::class);
        $this->app->singleton(ResetUserPassword::class);
        $this->app->singleton(UpdateUserPassword::class);
        $this->app->singleton(UpdateUserProfileInformation::class);
    }
}
