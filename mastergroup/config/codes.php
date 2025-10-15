<?php

return [
    // Префикс => [type, bonus_cps]
    // Заполни значения bonus_cps под себя
    'prefix_map' => [
        'AA' => ['type' => 'welcome',     'bonus_cps' => 10],
        'AB' => ['type' => 'promo',       'bonus_cps' => 20],
        'AC' => ['type' => 'gift',        'bonus_cps' => 30],
        'AD' => ['type' => 'compensation','bonus_cps' => 40],
        'AE' => ['type' => 'referral',    'bonus_cps' => 50],
    ],

    // Допустимые «человеческие» типы (валидатор/фильтры)
    'types' => ['welcome','promo','gift','compensation','referral'],

    // Максимум кодов за раз в форме добавления
    'max_bulk' => 20,

    // Формат кода: 2 буквы префикса + дальше буквы/цифры, длина 5..64 (регулируй)
    'regex' => '/^[A-Za-z]{2}[A-Za-z0-9]{3,62}$/',
];
