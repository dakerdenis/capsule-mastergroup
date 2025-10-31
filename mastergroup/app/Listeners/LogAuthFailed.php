<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\AuthActivityLog;
use Illuminate\Auth\Events\Failed;

class LogAuthFailed
{
    public function handle(Failed $event): void
    {
        // Логируем ТОЛЬКО для пользовательского guard'а
        if (($event->guard ?? 'web') !== 'web') {
            return;
        }

        // user_id пишем только если это именно User, иначе null
        $userId = ($event->user instanceof User) ? $event->user->id : null;

        AuthActivityLog::create([
            'user_id'    => $userId, // FK -> users.id; для не-User всегда null
            'guard'      => 'web',
            'event'      => 'login_failed',
            'email'      => is_array($event->credentials ?? null) ? ($event->credentials['email'] ?? null) : null,
            'ip'         => request()->ip(),
            'user_agent' => substr((string) request()->header('User-Agent'), 0, 1000),
            'created_at' => now(),
        ]);
    }
}
