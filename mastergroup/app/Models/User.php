<?php

namespace App\Models;

use App\Enums\ClientType;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable, MustVerifyEmail;

    protected $fillable = [
        'name',
        'full_name',
        'email',
        'password',
        'client_type',
        'birth_date',
        'gender',
        'country',
        'phone',
        'profile_photo_path',
        'identity_photo_path',
        'company_logo_path',
        'workplace',
        'instagram',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'birth_date'          => 'date',
            // если используешь enum PHP 8.1+
            'client_type'         => ClientType::class,
        ];
    }

    // Удобные геттеры/помощники
    public function isIndividual(): bool
    {
        return (string) $this->client_type === 'individual';
    }

    public function isCompany(): bool
    {
        return (string) $this->client_type === 'company';
    }
}
