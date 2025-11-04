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

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pakistan SMS Providers Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for various SMS providers that support Pakistan.
    | You can switch between providers by changing SMS_PROVIDER in .env
    |
    */
    'sms_providers' => [
        'smsto' => [
            'api_key' => env('SMSTO_API_KEY'),
            'sender_id' => env('SMSTO_SENDER_ID', 'RohiWala.pk'),
        ],
        'easysendsms' => [
            'api_key' => env('EASYSENDSMS_API_KEY'),
            'api_secret' => env('EASYSENDSMS_API_SECRET'),
            'sender_id' => env('EASYSENDSMS_SENDER_ID', 'RohiWala.pk'),
        ],
        'zigatext' => [
            'api_key' => env('ZIGATEXT_API_KEY'),
            'sender_id' => env('ZIGATEXT_SENDER_ID', 'RohiWala.pk'),
        ],
        'jazzcmt' => [
            'api_key' => env('JAZZCMT_API_KEY'),
            'sender_id' => env('JAZZCMT_SENDER_ID', 'RohiWala.pk'),
        ],
    ],

];
