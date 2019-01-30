# KeycloakAdapter

Adaptador de Keycloak para o Laravel. Esse adaptador utiliza os pacotes:

- ["paragonie/random_compat": `"v2.0.9"`](https://github.com/paragonie/random_compat)
- ["league/oauth2-client": `"2.2.1"`](https://github.com/thephpleague/oauth2-client)
- ["stevenmaguire/oauth2-keycloak": `"^2.1"`](https://github.com/stevenmaguire/oauth2-keycloak)

(as versões do `oauth2-client` e do `random_compat`foram fixadas devido a uma limitação do `oauth2-keycloak`)

Para projetos com login de SSO, utilizando o Keycloak em aplicações construídas com a framework Laravel.


## Instalação
O pacote não está hospedado em um repositório externo (p.ex. packagist), portanto, devemos configurar um repositório local para o composer.

No arquivo ```composer.json``` na raiz da sua aplicação, adicionar: 
        
``` js
   ...

    "repositories": {                                                                        //  <------ adicionar
        "local": {                                                                           //  <------ adicionar
            "type": "vcs",                                                                   //  <------ adicionar
            "url": "https://gitlab.pbh.gov.br/prodabel-laravel-pacotes/keycloak-adapter.git" //  <------ adicionar
        }                                                                                    //  <------ adicionar
    }                                                                                        //  <------ adicionar
    
    ...
    
    "require": {
        "php": "^7.1.3",
        "fideloper/proxy": "^4.0",
        "laravel/framework": "5.7.*",
        "laravel/tinker": "^1.0",
        "prodabel/keycloakadapter": "*"                                                      //  <------ adicionar
    },
    
    ...
    
    "autoload": {
        "psr-4": {
            "Prodabel\\KeycloakAdapter\\": "packages/Prodabel/KeycloakAdapter/src",          //  <------ adicionar
            "App\\": "app/"
        },
        "classmap": [
            "database/seeds",
            "database/factories"
        ]
    },
``` 

Agora sim, podemos baixar o pacote local, via Composer:

``` bash
$ composer update
```

## Configuração
Adicionar a middleware de rota `OpenidLogin` no arquivo `Kernel.php`

``` php
    ...
    
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'openid.login' => \Prodabel\KeycloakAdapter\OpenIdLogin::class,                     //  <---------- adicionar
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
    
    ...
```

No arquivo ```/config/auth```, acrescentar os drivers do `Keycloak`:

```php
return [
    'defaults' => [
        'guard' => 'keycloak',                          //  <------ adicionar
        'passwords' => 'users',
    ],
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],

        'keycloak' => [
            'driver' => 'kc_driver_guard',               //  <------ adicionar
            'provider' => 'kc_users',                    //  <------ adicionar
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        'kc_users' => [
            'driver' => 'kc_driver_provider',            //  <------ adicionar
        ],
    ],
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

];
```

A fim de publicar as configurações do `Keycloak`, executar o comando:
``` sh
$ php artisan vendor:publish --provider="Prodabel\KeycloakAdapter\KeycloakAdapterServiceProvider"
```
Esse comando, criará o arquivo `config/keycloak.php`

No arquivo ```.env```, adicionar:

``` bash
KEYCLOAK_AUTHSERVERURL=http://keycloak.qa.pbh/auth
KEYCLOAK_REALM=teste_cecilia
KEYCLOAK_CLIENTID=teste2_dsv
KEYCLOAK_CLIENTSECRET=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
KEYCLOAK_RSA_PUBLIC_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
KEYCLOAK_REDIRECTURI=http://localhost:7000/login
KEYCLOAK_REDIRECTLOGOUTURI=http://localhost:7000
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Credits

- [Cecilia Ribeiro](https://github.com/ceciliarb?tab=repositories)

## License

license. 
Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/prodabel/keycloakadapter.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/prodabel/keycloakadapter.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/prodabel/keycloakadapter/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/prodabel/keycloakadapter
[link-downloads]: https://packagist.org/packages/prodabel/keycloakadapter
[link-travis]: https://travis-ci.org/prodabel/keycloakadapter
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/prodabel
[link-contributors]: ../../contributors
