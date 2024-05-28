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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
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

    'calil' => [
        'app_key' => env('CALIL_APP_KEY'),
        'api_base_url' => 'https://api.calil.jp/',
    ],

    'google_books' => [
        'api_base_url' => 'https://www.googleapis.com/books/v1/volumes/',
    ],

    'resas' => [
        'api_key' => env('RESAS_API_KEY'),
        'api_base_url' => 'https://opendata.resas-portal.go.jp/api/v1/',
    ],

];
