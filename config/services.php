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
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'baidu' => [
        'client_id' => 'GywkzpCHA2KkG5PjPc5EOYpm',
        'client_secret' => 'sgt67lpnTZdeAzM0VvDBja65j8p20yPA',
    ],

    'baidu_censor' => [
        'client_id' => 'LbU9qt7LgzREtkg4u6SzjVhB',
        'client_secret' => 'W0uMTe9Y75j7AzZUvOTXKTnfWWuNSa1Y',
    ],

];
