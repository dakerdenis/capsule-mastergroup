<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // гость может обращаться к логину
    }

    public function rules(): array
    {
        return [
            'email'    => ['required','email','max:255'],
            'password' => ['required','string'],
            'remember' => ['sometimes','boolean'],
        ];
    }

    public function credentials(): array
    {
        return $this->only('email', 'password');
    }

    public function remember(): bool
    {
        return (bool) $this->boolean('remember');
    }
}
