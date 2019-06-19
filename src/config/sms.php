<?php

return [
    'twilio' => [
    /*
    |--------------------------------------------------------------------------
    | Twilio  number
    |--------------------------------------------------------------------------
    |
    | This the main number to send from. You can send from other numbers.
    | This is only a default.
    |
    */
    'default_from_number' => env('TWILIO_DEFAULT_FROM_NUMBER'),
    /*
    |--------------------------------------------------------------------------
    | Twilio SID
    |--------------------------------------------------------------------------
    |
    | This SID of the main number to send from. You can send from other numbers.
    | This is only a default.
    |
    */
    'default_from_sid' => env('TWILIO_DEFAULT_FROM_SID'),
    /*
    |--------------------------------------------------------------------------
    | Twilio Account SID
    |--------------------------------------------------------------------------
    |
    | This Account SID from Twilio
    |
    */
    'account_sid' => env('TWILIO_ACCOUNT_SID'),
    /*
    |--------------------------------------------------------------------------
    | Twilio Auth Token
    |--------------------------------------------------------------------------
    |
    | This Twilio auth token. You can get this from the Twilio dashboard.
    |
    */
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
    ]
];
