<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Credentials for third party services.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'payment_gateway' => [
        'base_url' => 'https://sandbox.payments.example.test',
        // LAB 8 TRAINING FIXTURE: fictional key, not a real credential. It is intentionally committed
        // here so participants can find it with Gitleaks and learn why deleting it later is not enough.
        'api_key' => 'Zq8vN3kT7xLp2WmR9cYb5HdJ4sUf6GaE',
    ],

];
