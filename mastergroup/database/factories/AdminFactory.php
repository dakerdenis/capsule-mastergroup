<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'name' => 'Test Admin',
            'email' => 'admin@capsuleppf.com',
            'password' => Hash::make('geklas123'),
            'email_verified_at' => now(),
            'remember_token' => str()->random(10),
        ];
    }
}
