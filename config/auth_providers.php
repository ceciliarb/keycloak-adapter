<?php

return [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\User::class,
    ],

    'kc_users' => [
        'driver' => 'kc_driver_provider',
    ],
];
