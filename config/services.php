<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'telegram' => [
        'token'        => env('TELEGRAM_TOKEN'),
        'bot_username' => env('TELEGRAM_BOT_USERNAME', '@SuitPaymentsBot'),
        'base_url'     => env('TELEGRAM_BASE_URL', 'https://api.telegram.org/bot'),
    ],

    'mail' => [
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS'),
            'name'    => env('MAIL_FROM_NAME'),
        ],
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'suitpay' => [
        'base_url'          => env('SUITPAY_BASE_URL'),
        'client_id'         => env('SUITPAY_CLIENT_ID'),
        'client_secret'     => env('SUITPAY_CLIENT_SECRET'),
        'banking_url'       => env('SUITPAY_BANKING_URL'),
        'username_checkout' => env('USERNAME_CHECKOUT'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_CALLBACK_URL'),
    ],

    'facebook_pixel' => [
        'base_url' => env('FACEBOOK_PIXEL_BASE_URL'),
    ],

    'utmify' => [
        'base_url' => env('UTMIFY_BASE_URL', 'https://api.utmify.com.br'),
    ],

    'singularcdn' => [
        'base_url'  => env('SINGULARCDN_BASE_URL'),
        'api_token' => env('API_TOKEN_SINGULARCDN'),
        'chave'     => env('CHAVE_SINGULARCDN'),
        'url'       => env('URL_SINGULARCDN'),
        'backend'   => env('BACKEND_SINGULARCDN'),
    ],

    'memberKit' => [
        'base_url' => env('MEMBERKIT_BASE_URL'),
    ],

    'reverb' => [
        'reverb_app_id'     => env('REVERB_APP_ID'),
        'reverb_app_key'    => env('REVERB_APP_KEY'),
        'reverb_app_secret' => env('REVERB_APP_SECRET'),
        'reverb_host'       => env('REVERB_HOST'),
        'reverb_port'       => env('REVERB_PORT'),
    ],

    'google_analytics_checkout' => [
        'view_id'     => env('GOOGLE_ANALYTICS_CHECKOUT_VIEW_ID', '489543098'),
        'tag_tracker' => env('GOOGLE_ANALYTICS_CHECKOUT_TRACKER', 'G-V9KBDJVKCS'),
    ],

    'google_analytics_sales' => [
        'view_id'     => env('GOOGLE_ANALYTICS_SALES_VIEW_ID', '489566671'),
        'tag_tracker' => env('GOOGLE_ANALYTICS_SALES_TRACKER', 'G-RYCBMGY769'),
    ],

    'pusher' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
    ],
    'messageBroker' => [
        'url'   => env('NOTIFICATION_API'),
        'token' => env('NOTIFICATION_TOKEN'),
        'key'   => env('NOTIFICATION_KEY'),
    ],
    'members' => [
        'url'                   => env('MEMBERS_API'),
        'urlFront'              => env('MEMBERS_FRONT'),
        'token'                 => env('MEMBERS_TOKEN'),
        'suit_academy_products' => env('SUIT_ACADEMY_PRODUCTS_UUIDS'),
    ],
    'webhookn8n' => [
        'url' => env('VITE_N8N_WEBHOOK_URL'),
    ],

    'awsUrl' => env('AWS_URL'),

    'cloudflare' => [
        'base_url' => env('CLOUDFLARE_BASE_URL', 'https://api.cloudflare.com/client/v4/'),
        'stream'   => [
            'api_token'  => env('CLOUDFLARE_STREAM_API_TOKEN'),
            'account_id' => env('CLOUDFLARE_ACCOUNT_ID'),
        ],
    ],

];
