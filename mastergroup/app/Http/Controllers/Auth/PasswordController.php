<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetGenerated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    public function forgot()
    {
        return view('auth.passwords.forgot', ['title' => 'Forgot password']);
    }

    // НОВЫЙ endpoint — генерим пароль и отправляем
    public function generateNew(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','string','email:rfc','max:255'],
        ]);

        $user = User::where('email', $data['email'])->first();

        $msg = 'If your email is registered, a new password has been sent.';

        if (!$user) {
            return back()->with('status', $msg);
        }

        $password = self::strongPassword(14);

        // 'password' => 'hashed' каст в модели захеширует сам
        $user->password = $password;
        $user->save();

        Mail::to($user->email)->send(
            new PasswordResetGenerated($user->name ?: $user->email, $password)
        );

        return back()->with('status', $msg);
    }

    private static function strongPassword(int $len = 14): string
    {
        $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $digits  = '23456789';
        $symbols = '!@#$%^&*()';

        $all = $letters.$digits.$symbols;

        $need = [
            $letters[random_int(0, strlen($letters)-1)],
            $letters[random_int(0, strlen($letters)-1)],
            $digits[random_int(0, strlen($digits)-1)],
            $symbols[random_int(0, strlen($symbols)-1)],
        ];
        while (count($need) < $len) {
            $need[] = $all[random_int(0, strlen($all)-1)];
        }
        for ($i = count($need)-1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$need[$i], $need[$j]] = [$need[$j], $need[$i]];
        }
        return implode('', $need);
    }
}
