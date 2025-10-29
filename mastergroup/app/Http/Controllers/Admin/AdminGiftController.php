<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGiftController extends Controller
{
    public function __construct(protected SmsService $sms) {}

    /** Форма */
    public function gift(Request $request)
    {
        $users = User::query()
            ->select('id','full_name','email','phone','country')
            ->latest('id')
            ->limit(20)
            ->get();

        return view('admin.gift', [
            'title' => 'Gift CPS',
            'users' => $users,
        ]);
    }

    /** Начислить (БЕЗ логирования в codes) */
    public function store(Request $request)
    {
        $data = $request->validate([
            'identifier' => ['required','string','max:255'], // email или ID
            'amount'     => ['required','integer','min:1','max:100000'],
            'note'       => ['nullable','string','max:500'],
        ]);

        $ident = trim($data['identifier']);
        $user = filter_var($ident, FILTER_VALIDATE_INT)
            ? User::find((int)$ident)
            : User::where('email', $ident)->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors(['identifier' => 'User not found by this email or ID.']);
        }

        try {
            // только увеличение баланса пользователя, никаких записей в других таблицах
            DB::transaction(function () use ($user, $data) {
                $user->increment('cps_total', (int)$data['amount']);
            });
            $user->refresh();

            // СМС — сообщаем новое значение
            if (!empty($user->phone)) {
                $phone = $this->sms->normalizePhone((string)$user->phone);
                if ($phone) {
                    $msg = "CAPSULE PPF: Your balance has been replenished by {$data['amount']} CPS. New balance: {$user->cps_total}.";
                    try { $this->sms->send($phone, $msg); } catch (\Throwable $e) { /* молча игнорируем */ }
                }
            }

            return back()->with('status', "Gift sent: +{$data['amount']} CPS. New balance: {$user->cps_total}");

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['form' => 'Failed to gift CPS. Try again.']);
        }
    }
}
