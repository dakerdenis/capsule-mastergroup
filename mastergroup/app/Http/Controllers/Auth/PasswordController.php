<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetGenerated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function forgot()
    {
        return view('auth.passwords.forgot', [
            'title' => 'Forgot password',
        ]);
    }

    // Генерим новый сложный пароль, сохраняем и отправляем на e-mail
    public function sendLink(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','string','email:rfc','max:255'],
        ]);

        $user = User::where('email', $data['email'])->first();

        // одинаковый ответ чтобы не палить существование почты
        $statusMsg = 'If your email is registered, a new password has been sent.';

        if (!$user) {
            return back()->with('status', $statusMsg);
        }

        // генерим сильный пароль
        $newPassword = self::generateStrongPassword(14);

        // сохраняем (cast 'password' => 'hashed' в модели сам захеширует)
        $user->password = $newPassword;
        $user->save();

        // отправляем письмо
        Mail::to($user->email)->send(new PasswordResetGenerated($user->name ?: $user->email, $newPassword));

        return back()->with('status', $statusMsg);
    }

    // Не используется в этой схеме
    public function reset(Request $request, string $token)
    {
        return view('auth.passwords.reset', [
            'title' => 'Reset password',
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function update(Request $request)
    {
        return back()->with('status', 'Password reset is not implemented yet.');
    }

    private static function generateStrongPassword(int $length = 14): string
    {
        $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $digits  = '23456789';
        $symbols = '!@#$%^&*()-_=+[]{},.?';

        $all = $letters.$digits.$symbols;

        $need = [
            $letters[random_int(0, strlen($letters)-1)],
            $letters[random_int(0, strlen($letters)-1)],
            $digits[random_int(0, strlen($digits)-1)],
            $symbols[random_int(0, strlen($symbols)-1)],
        ];

        while (count($need) < $length) {
            $need[] = $all[random_int(0, strlen($all)-1)];
        }

        // перемешать
        for ($i = count($need)-1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$need[$i], $need[$j]] = [$need[$j], $need[$i]];
        }

        return implode('', $need);
    }
}
