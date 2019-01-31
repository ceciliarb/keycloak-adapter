<?php

return [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'token',
        'provider' => 'users',
    ],

    'keycloak' => [
        'driver' => 'kc_driver_guard',
        'provider' => 'kc_users',
    ],
];
