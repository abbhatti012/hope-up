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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id' => '905961464959-od680h69cf6322ohvt7bm9h19t0rip47.apps.googleusercontent.com',
        'client_secret' => 'lAN-An9JaC8Z7ZzBfh1M6z6o',
        'redirect' => 'http://aaam.baratomovel.pt/auth/google/callback',
    ],
    'facebook' => [
        'client_id' => '402055334298199',
        'client_secret' => '71adddf5a1c25a2b8cf781dff2f3ca2a',
        'redirect' => 'http://aaam.baratomovel.pt/auth/facebook/callback',
      ],

];
