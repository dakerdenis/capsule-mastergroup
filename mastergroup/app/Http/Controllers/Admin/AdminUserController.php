<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserApprovedMail;
use App\Mail\UserRejectedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\SmsService;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByRaw("FIELD(status, 'pending','approved','rejected')")
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'title' => 'Users',
            'users' => $users,
        ]);
    }

    public function show(User $user)
    {
        return view('admin.users.show', [
            'title' => 'User #' . $user->id,
            'user'  => $user,
        ]);
    }


    public function updateStatus(Request $request, User $user)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'rejected_reason' => 'nullable|string|max:255',
        ]);

        $oldStatus = (string)($user->status ?? 'pending');
        $newStatus = $data['status'];

        Log::info('Admin updateStatus called', [
            'user_id'    => $user->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'email'      => $user->email,
            'phone_raw'  => $user->phone,
        ]);

        // Обновляем модель
        $user->status = $newStatus;

        if ($newStatus === 'approved') {
            $user->approved_at = now();
            $user->rejected_reason = null;
        } elseif ($newStatus === 'rejected') {
            $user->rejected_reason = $data['rejected_reason'] ?? null;
            $user->approved_at = null;
        } else { // pending
            $user->approved_at = null;
        }

        $user->save();

        Log::info('User saved after status change', [
            'user_id' => $user->id,
            'approved_at' => optional($user->approved_at)->toDateTimeString(),
            'rejected_reason' => $user->rejected_reason,
        ]);

        // Отправки только если статус реально изменился
        if ($oldStatus !== $newStatus) {
            Log::info('Status changed, will notify', [
                'user_id' => $user->id,
                'to_email' => $user->email,
                'to_phone' => $user->phone,
            ]);

            try {
                if ($newStatus === 'approved') {
                    // EMAIL — отправляем сразу (без очереди), чтобы точно ушло в этом же запросе
                    if (!empty($user->email)) {
                        Mail::to($user->email)->send(new UserApprovedMail($user));
                        Log::info('Approved email sent', ['user_id' => $user->id, 'email' => $user->email]);
                    } else {
                        Log::warning('Approved email skipped (empty email)', ['user_id' => $user->id]);
                    }

                    // SMS
                    if (!empty($user->phone)) {
                        $normalized = $this->normalizePhone($user->phone);
                        Log::info('Sending approved SMS', ['user_id' => $user->id, 'phone_normalized' => $normalized]);
                        $smsOk = $this->sendSmsNotification($normalized, $this->approvedSmsText($user));
                        Log::info('Approved SMS result', ['user_id' => $user->id, 'ok' => $smsOk]);
                    } else {
                        Log::warning('Approved SMS skipped (empty phone)', ['user_id' => $user->id]);
                    }
                } elseif ($newStatus === 'rejected') {
                    if (!empty($user->email)) {
                        Mail::to($user->email)->send(new UserRejectedMail($user, $user->rejected_reason));
                        Log::info('Rejected email sent', ['user_id' => $user->id, 'email' => $user->email]);
                    } else {
                        Log::warning('Rejected email skipped (empty email)', ['user_id' => $user->id]);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Post-status notification failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::info('Status unchanged, no notifications will be sent', [
                'user_id' => $user->id,
                'status'  => $newStatus,
            ]);
        }

        return back()->with('status', 'User status updated.');
    }

    /**
     * Нормализация номера (простая, подправь под свои требования)
     */
    private function normalizePhone(string $phone): string
    {
        $onlyDigits = preg_replace('/\D+/', '', $phone ?? '');
        // если номер без кода — добавь по своим правилам
        if (strlen($onlyDigits) === 9) { // например, местный без кода страны
            $onlyDigits = '994' . $onlyDigits; // пример для Азербайджана
        }
        if (str_starts_with($onlyDigits, '0')) {
            $onlyDigits = ltrim($onlyDigits, '0');
        }
        return $onlyDigits;
    }

    /**
     * Красивый SMS-текст (EN)
     */
    private function approvedSmsText(User $user): string
    {
        $name = $user->full_name ?? $user->name ?? '';
        $hello = $name ? "Hi, {$name}!" : "Hi!";
        return $hello . " Your access to CAPSULE PPF has been approved. You can now sign in and start using the system. — CAPSULE PPF";
    }

    /**
     * Отправка SMS через API (перенеси креды в .env)
     */
    private function sendSmsNotification(?string $clientPhone, string $message): bool
    {
        if (empty($clientPhone)) {
            Log::warning('SMS skipped: empty phone');
            return false;
        }

        $apiUrl      = config('services.capsule_sms.url', 'https://sms.atatexnologiya.az/bulksms/api');
        $apiLogin    = config('services.capsule_sms.login', 'Capsule');
        $apiPassword = config('services.capsule_sms.password', 'changeme');
        $title       = config('services.capsule_sms.title', 'CAPSULE PPF');
        $controlId   = time() . rand(1000, 9999);

        $cfgLogin = config('services.capsule_sms.login');
        $cfgPass  = config('services.capsule_sms.password');
        $cfgTitle = config('services.capsule_sms.title');

        Log::info('SMS config resolved', [
            'login'   => $cfgLogin,
            'pass_len' => strlen($cfgPass),   // длина пароля (не логируем сам пароль)
            'title'   => $cfgTitle,
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
                    <msisdn>{$clientPhone}</msisdn>
                    <message>" . htmlspecialchars($message, ENT_XML1 | ENT_COMPAT, 'UTF-8') . "</message>
                </body>
            </request>";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/xml',
            ])->withBody($xmlData, 'application/xml')
                ->withOptions(['verify' => false])
                ->post($apiUrl);

            Log::info('SMS API Request', ['xml' => $xmlData]);
            Log::info('SMS API Response', ['response' => $response->body()]);

            if (strpos($response->body(), '<responsecode>000</responsecode>') !== false) {
                return true;
            }

            Log::error('SMS sending failed', ['response' => $response->body()]);
            return false;
        } catch (\Throwable $e) {
            Log::error('SMS API Error: ' . $e->getMessage());
            return false;
        }
    }
}
