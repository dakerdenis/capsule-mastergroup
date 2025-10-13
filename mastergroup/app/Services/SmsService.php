<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function normalizePhone(?string $phone): ?string
    {
        if (!$phone) return null;
        $only = preg_replace('/\D+/', '', $phone);

        // пример для Азербайджана — подстрои под себя
        if (strlen($only) === 9) {
            $only = '994' . $only;
        }
        if (str_starts_with($only, '0')) {
            $only = ltrim($only, '0');
        }
        return $only ?: null;
    }

    public function send(string $msisdn, string $message): bool
    {
        $apiUrl      = config('services.capsule_sms.url');
        $apiLogin    = config('services.capsule_sms.login');
        $apiPassword = config('services.capsule_sms.password');
        $title       = config('services.capsule_sms.title');
        $controlId   = time() . rand(1000, 9999);

        if (!$apiUrl || !$apiLogin || !$apiPassword || !$title) {
            Log::error('SMS config missing', compact('apiUrl','apiLogin','apiPassword','title'));
            return false;
        }

        // контрольные логи без раскрытия секрета
        Log::info('SMS config resolved', [
            'login'    => $apiLogin,
            'pass_len' => strlen($apiPassword),
            'title'    => $title,
        ]);

        $xmlData = "<?xml version='1.0' encoding='UTF-8'?>
            <request>
                <head>
                    <operation>submit</operation>
                    <login>{$apiLogin}</login>
                    <password>{$apiPassword}</password>
                    <title>{$title}</title>
                    <scheduled>now</scheduled>
                    <isbulk>false</isbulk>
                    <controlid>{$controlId}</controlid>
                </head>
                <body>
                    <msisdn>{$msisdn}</msisdn>
                    <message>" . htmlspecialchars($message, ENT_XML1 | ENT_COMPAT, 'UTF-8') . "</message>
                </body>
            </request>";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/xml'])
                ->withBody($xmlData, 'application/xml')
                ->withOptions(['verify' => false])
                ->post($apiUrl);

            Log::info('SMS API Request', ['xml' => $xmlData]);
            Log::info('SMS API Response', ['response' => $response->body()]);

            return strpos($response->body(), '<responsecode>000</responsecode>') !== false;
        } catch (\Throwable $e) {
            Log::error('SMS API Error: '.$e->getMessage());
            return false;
        }
    }
}
