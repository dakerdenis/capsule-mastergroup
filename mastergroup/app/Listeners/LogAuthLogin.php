<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\AuthActivityLog;
use Illuminate\Auth\Events\Login;

class LogAuthLogin
{
    public function handle(Login $event): void
    {
        if (($event->guard ?? 'web') !== 'web') {
            return;
        }

        $userId = ($event->user instanceof User) ? $event->user->id : null;
        $email  = ($event->user instanceof User) ? $event->user->email : null;

        AuthActivityLog::create([
            'user_id'    => $userId,
            'guard'      => 'web',
            'event'      => 'login',
            'email'      => $email,
            'ip'         => request()->ip(),
            'user_agent' => substr((string) request()->header('User-Agent'), 0, 1000),
            'created_at' => now(),
        ]);
    }
}
