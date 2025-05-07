<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard'    => env('AUTH_GUARD', 'web'),
        'passwords'=> env('AUTH_PASSWORD_BROKER', 'providers'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        // Guard para Prestador
        'web' => [
            'driver'   => 'session',
            'provider' => 'providers',
        ],

        // Guard para Cliente (CustomUser)
        'custom' => [
            'driver'   => 'session',
            'provider' => 'custom_users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        // Provider para tabela providers
        'providers' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Provider::class,
        ],

        // Provider para tabela custom_users
        'custom_users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\CustomUser::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        // Reset de senha prestadores
        'providers' => [
            'provider' => 'providers',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],

        // Reset de senha clientes
        'custom_users' => [
            'provider' => 'custom_users',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
