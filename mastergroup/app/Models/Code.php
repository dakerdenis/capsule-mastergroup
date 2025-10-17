<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ← добавить

class Code extends Model
{
    use HasFactory; // ← добавить
    protected $fillable = [
        'code', 'type', 'status', 'bonus_cps',
        'activated_by_user_id', 'activated_at',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
    ];

    public function activatedBy()
    {
        return $this->belongsTo(User::class, 'activated_by_user_id');
    }

    // Автоматически приводим code к верхнему регистру и выставляем type/bonus_cps по префиксу
    protected static function booted()
    {
        static::saving(function (Code $m) {
            $m->code = strtoupper(trim((string)$m->code));

            $prefix = substr($m->code, 0, 2);
            $map = config('codes.prefix_map');

            if (isset($map[$prefix])) {
                $m->type = $map[$prefix]['type'];
                // НЕ трогаем bonus_cps если он уже задан — это позволяет вручную переопределить
                if (is_null($m->bonus_cps)) {
                    $m->bonus_cps = (int) $map[$prefix]['bonus_cps'];
                }
            }
        });
    }

    public function activateForUser(User $user): void
    {
        if ($this->status === 'activated') return;

        $this->forceFill([
            'status'               => 'activated',
            'activated_by_user_id' => $user->id,
            'activated_at'         => now(),
        ])->save();
    }

    // Скоупы для фильтров
    public function scopeStatus($q, ?string $status)
    {
        if (in_array($status, ['new','activated'], true)) {
            $q->where('status', $status);
        }
        return $q;
    }

    public function scopeType($q, ?string $type)
    {
        $types = config('codes.types', []);
        if ($type && in_array($type, $types, true)) {
            $q->where('type', $type);
        }
        return $q;
    }

    public function isNew(): bool       { return $this->status === 'new'; }
    public function isActivated(): bool { return $this->status === 'activated'; }
}
