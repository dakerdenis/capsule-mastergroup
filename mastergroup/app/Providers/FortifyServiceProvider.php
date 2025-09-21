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
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::registerView(fn() => view('auth.register'));
        Fortify::requestPasswordResetLinkView(fn() => view('auth.passwords.forgot'));
        Fortify::resetPasswordView(fn ($request) => view('auth.passwords.reset', ['token' => $request->route('token'), 'email' => $request->email]));
    
        // Кастомные экшены
        $this->app->singleton(CreateNewUser::class);
        $this->app->singleton(ResetUserPassword::class);
        $this->app->singleton(UpdateUserPassword::class);
        $this->app->singleton(UpdateUserProfileInformation::class);
    }
}
