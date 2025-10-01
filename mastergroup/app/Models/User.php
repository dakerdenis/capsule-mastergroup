<?php

namespace App\Models;

use App\Enums\ClientType;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name','full_name','email','password','client_type','birth_date','gender','country','phone',
        'profile_photo_path','identity_photo_path','company_logo_path','workplace','instagram',
        'status','approved_at','rejected_reason',
        'cps_total',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'birth_date'        => 'date',
            'approved_at'       => 'datetime',
            'client_type'       => ClientType::class,
            'cps_total'         => 'integer',
        ];
    }

    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    // ✅ сравниваем с enum
    public function isIndividual(): bool
    {
        return $this->client_type === ClientType::INDIVIDUAL;
    }

    public function isCompany(): bool
    {
        return $this->client_type === ClientType::COMPANY;
    }

    // (опционально) если где-то нужно получить строковое значение:
    public function clientTypeValue(): ?string
    {
        return $this->client_type?->value; // вернёт 'individual' | 'company' | null
    }
}
