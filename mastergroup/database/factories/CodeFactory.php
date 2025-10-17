<?php

namespace Database\Factories;

use App\Models\Code;
use Illuminate\Database\Eloquent\Factories\Factory;

class CodeFactory extends Factory
{
    protected $model = Code::class;

    public function definition(): array
    {
        // Берём карту префиксов из конфига (если нет — дефолт)
        $map = (array) config('codes.prefix_map', [
            'AA' => ['type' => 'welcome',      'bonus_cps' => 10],
            'AB' => ['type' => 'promo',        'bonus_cps' => 20],
            'AC' => ['type' => 'gift',         'bonus_cps' => 30],
            'AD' => ['type' => 'compensation', 'bonus_cps' => 40],
            'AE' => ['type' => 'referral',     'bonus_cps' => 50],
        ]);

        $prefixes = array_keys($map);
        $prefix   = $prefixes[array_rand($prefixes)];

        // 2 буквы + ещё 8 символов [A-Z0-9]
        $tail = $this->faker->regexify('[A-Z0-9]{8}');
        $code = $prefix.$tail;

        return [
            'code'                  => $code,
            // type/bonus_cps поставит модель в booted() по префиксу
            'status'                => 'new',
            'activated_by_user_id'  => null,
            'activated_at'          => null,
        ];
    }

    /**
     * Состояние "активирован" — метим как activated (user/время зададим в сидере)
     */
    public function activated(): self
    {
        return $this->state(function () {
            return [
                'status'       => 'activated',
                'activated_at' => now(),
            ];
        });
    }
}
