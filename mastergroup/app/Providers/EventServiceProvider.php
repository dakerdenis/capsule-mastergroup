<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Listeners\SendAdminSmsOnRegistration;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Listeners\LogAuthLogin;
use App\Listeners\LogAuthLogout;
use App\Listeners\LogAuthFailed;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendAdminSmsOnRegistration::class,
        ],
        Login::class  => [LogAuthLogin::class],
        Logout::class => [LogAuthLogout::class],
        Failed::class => [LogAuthFailed::class],
    ];
}
