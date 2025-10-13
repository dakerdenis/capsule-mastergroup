<?php

namespace App\Listeners;

use App\Services\SmsService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class SendAdminSmsOnRegistration
{
    public function __construct(protected SmsService $sms) {}

    public function handle(Registered $event): void
    {
        $user = $event->user;

        // Берём номер администратора из нескольких источников
        $adminPhoneRaw =
            config('services.admin.alert_phone')
            ?? config('app.admin_phone')
            ?? env('ADMIN_ALERT_PHONE')
            ?? env('ADMIN_PHONE');

        $adminPhone = $this->sms->normalizePhone((string)($adminPhoneRaw ?? ''));

        if (!$adminPhone) {
            Log::warning('Admin SMS skipped: empty/invalid admin phone', ['raw' => $adminPhoneRaw]);
            return;
        }

        $name    = (string)($user->full_name ?? $user->name ?? '');
        $typeRaw = $user->client_type ?? '';
        $type    = is_object($typeRaw) ? ($typeRaw->value ?? (string)$typeRaw) : (string)$typeRaw;
        $email   = (string)($user->email ?? '');
        $phone   = (string)($user->phone ?? '');
        $country = (string)($user->country ?? '');
        $id      = (string)$user->id;

        $msg = "CAPSULE PPF: New {$type} registration — #{$id} {$name}. Email: {$email}. Phone: {$phone}. Country: {$country}. Status: pending.";

        $this->sms->send($adminPhone, $msg);
    }
}
