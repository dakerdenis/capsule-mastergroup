<?php

return [


    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'capsule_sms' => [
        'url'      => env('CAPSULE_SMS_URL', 'https://sms.atatexnologiya.az/bulksms/api'),
        'login'    => env('CAPSULE_SMS_LOGIN', 'Capsule'),
        'password' => env('CAPSULE_SMS_PASSWORD', 'changeme'),
        'title'    => env('CAPSULE_SMS_TITLE', 'CAPSULE PPF'),
    ],
    'admin' => [
        'alert_phone' => env('ADMIN_ALERT_PHONE'),
    ],


];
