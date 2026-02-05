<?php

return [
    // Префикс => [type, bonus_cps]
    // Заполни значения bonus_cps под себя
    'prefix_map' => [
        'UR' => ['type' => 'urban',     'bonus_cps' => 20],
        'OP' => ['type' => 'optima',       'bonus_cps' => 25],
        'EL' => ['type' => 'element',        'bonus_cps' => 30],
        'HU' => ['type' => 'hurrican','bonus_cps' => 40],
        'MA' => ['type' => 'matte',    'bonus_cps' => 30],
    ],

    // Допустимые «человеческие» типы (валидатор/фильтры)
    'types' => ['urban','optima','element','hurrican','matte'],

    // Максимум кодов за раз в форме добавления
    'max_bulk' => 20,

    // Формат кода: 2 буквы префикса + дальше буквы/цифры, длина 5..64 (регулируй)
    'regex' => '/^[A-Za-z]{2}[A-Za-z0-9]{3,62}$/',
];
