<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * /register — экран выбора типа регистрации (individual/company)
     * View: resources/views/auth/register.blade.php (у тебя уже есть)
     */
    public function choose()
    {
        return view('auth.register', [
            'title' => 'Create account',
        ]);
    }

    /**
     * /register/user — форма регистрации частного лица
     * View: resources/views/auth/register_user.blade.php (положишь свой HTML позже)
     */
    public function showUser()
    {
        return view('auth.register_user', [
            'title' => 'Register as Individual',
        ]);
    }

    /**
     * /register/company — форма регистрации компании
     * View: resources/views/auth/register_company.blade.php (положишь свой HTML позже)
     */
    public function showCompany()
    {
        return view('auth.register_company', [
            'title' => 'Register as Company',
        ]);
    }

    /**
     * POST /register — сохранение формы регистрации
     * Пока без Request/валидации/логики — оставляем безопасную заглушку.
     * Когда пришлёшь поля — допишем.
     */
    public function store(Request $request)
    {
        // TODO: добавить валидацию и создание пользователя после того, как уточним поля.
        // Временная заглушка, чтобы было понятно, что endpoint живой:
        return back()->with('status', 'Registration is not implemented yet. Send the field list and we will finish it.');
    }
}
