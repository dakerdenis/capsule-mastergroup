<?php
// config/mail.php
return [
    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport'    => 'smtp',
            'host'         => env('MAIL_HOST', '127.0.0.1'),
            'port'         => env('MAIL_PORT', 465),
            'encryption'   => env('MAIL_ENCRYPTION', 'ssl'),
            'username'     => env('MAIL_USERNAME'),
            'password'     => env('MAIL_PASSWORD'),
            'timeout'      => 15,
            'local_domain' => env(
                'MAIL_EHLO_DOMAIN',
                parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)
            ),

            // СНИЖАЕМ проверку сертификатов ТОЛЬКО в local
            'stream' => env('APP_ENV') === 'local' ? [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                    // фикс на старых OpenSSL: принудим TLS 1.2/1.3
                    'crypto_method'     => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT
                                          | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT,
                ],
            ] : [],
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers'   => ['smtp', 'log'],
        ],

        'log' => [
            'transport' => 'log',
            'channel'   => env('MAIL_LOG_CHANNEL'),
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name'    => env('MAIL_FROM_NAME', 'Example'),
    ],
];
