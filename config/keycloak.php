<?php

return [
    'authServerUrl'         => env('KEYCLOAK_AUTHSERVERURL'),
    'realm'                 => env('KEYCLOAK_REALM'),
    'clientId'              => env('KEYCLOAK_CLIENTID'),
    'clientSecret'          => env('KEYCLOAK_CLIENTSECRET'),
    'rsa_public_key'        => env('KEYCLOAK_RSA_PUBLIC_KEY'),
    'redirectUri'           => env('KEYCLOAK_REDIRECTURI'),
    'redirectLogoutUri'     => env('KEYCLOAK_REDIRECTLOGOUTURI')
    // 'scope'                 => 'openid,email,name,profile',
];
