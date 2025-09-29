<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // куда кидать НЕавторизованных (для 'auth' мидлвара)
        $middleware->redirectGuestsTo(fn () => route('auth.login'));

        // куда кидать уже авторизованных при попытке открыть гостевые роуты (middleware 'guest')
        $middleware->redirectUsersTo(fn () => route('account.dashboard'));

        // используем твой кастомный guest-мидлвар
        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            // 'auth' оставляем дефолтным (Illuminate\Auth\Middleware\Authenticate)
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
