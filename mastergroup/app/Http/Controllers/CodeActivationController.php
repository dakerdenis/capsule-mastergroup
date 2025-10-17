<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CodeActivationController extends Controller
{
    public function activate(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'code' => ['required', 'string', 'max:128'],
        ]);

        $raw = strtoupper(trim((string)$request->input('code')));
        $regex = '/^[A-Z][A-Z0-9]{3,}$/';

        if (!preg_match($regex, $raw)) {
            return response()->json([
                'ok'    => false,
                'error' => 'INVALID_FORMAT',
                'message' => 'Invalid code format.',
            ], 422);
        }

        try {
            $result = DB::transaction(function () use ($raw, $user) {
                /** @var Code|null $code */
                $code = Code::query()
                    ->where('code', $raw)
                    ->lockForUpdate()
                    ->first();

                if (!$code) {
                    return [
                        'ok' => false,
                        'status' => 404,
                        'error' => 'NOT_FOUND',
                        'message' => 'Code not found.',
                    ];
                }

                if ($code->status === 'activated') {
                    return [
                        'ok' => false,
                        'status' => 409,
                        'error' => 'ALREADY_USED',
                        'message' => 'This code has already been activated.',
                        'activated_by' => $code->activated_by_user_id,
                        'activated_at' => optional($code->activated_at)->toDateTimeString(),
                    ];
                }

                // Подстраховка: если в таблице нет bonus_cps — вычислить по префиксу
                $bonus = (int)($code->bonus_cps ?? 0);
                if ($bonus <= 0) {
                    $map = (array) config('codes.prefix_map', []);
                    $prefix = substr($code->code, 0, 2);
                    if (isset($map[$prefix]['bonus_cps'])) {
                        $bonus = (int) $map[$prefix]['bonus_cps'];
                    }
                }
                if ($bonus <= 0) {
                    return [
                        'ok' => false,
                        'status' => 422,
                        'error' => 'NO_BONUS',
                        'message' => 'This code has no bonus configured.',
                    ];
                }

                // Активируем код
                $code->forceFill([
                    'status'               => 'activated',
                    'activated_by_user_id' => $user->id,
                    'activated_at'         => now(),
                ])->save();

                // Начисляем пользователю
                $user->increment('cps_total', $bonus);
                $user->refresh();

                return [
                    'ok'           => true,
                    'code'         => $code->code,
                    'type'         => $code->type,
                    'bonus_cps'    => $bonus,
                    'activated_at' => optional($code->activated_at)->toDateTimeString(),
                    'new_cps'      => (int) $user->cps_total,
                ];
            });

            if (!$result['ok']) {
                return response()->json($result, $result['status'] ?? 422);
            }

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'error' => 'SERVER_ERROR',
                'message' => 'Failed to activate the code. Try again later.',
            ], 500);
        }
    }
}
