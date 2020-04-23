<?php

return [

    // Exceptions not reported
    'exclude' => [
        Illuminate\Auth\AuthenticationException::class,
    ],

    // Send one email per specific exceptions every X seconds
    'throttle' => 60,

    // From address for the email
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    // To address for the email
    'to' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    // Email subject
    'subject' => sprintf('[%s] New exception', env('APP_NAME', 'Laravel')),

];
