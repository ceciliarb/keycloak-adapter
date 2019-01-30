# KeycloakAdapter

Adaptador de Keycloak para o Laravel. 

Para projetos com login de SSO, utilizando o Keycloak.


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
Obs. Este pacote depende de 2 outros pacotes:
``` bash
"league/oauth2-client": "2.2.1",
"paragonie/random_compat": "v2.0.9",
"stevenmaguire/oauth2-keycloak": "^2.1"
```
As versões do `oauth2-client` e do `random_compat`foram fixadas devido a uma limitação do `oauth2-keycloak`


## Configuração

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

## Usage

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

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
