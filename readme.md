# KeycloakAdapter

Adaptador de Keycloak para o Laravel. 

Para projetos com login de SSO, utilizando o Keycloak.


## Instalação

Via Composer

``` bash
$ composer require prodabel/keycloakadapter
```

No arquivo ```.env```

``` bash
KEYCLOAK_AUTHSERVERURL=http://keycloak.qa.pbh/auth
KEYCLOAK_REALM=teste_cecilia
KEYCLOAK_CLIENTID=teste2_dsv
KEYCLOAK_CLIENTSECRET=4f893a00-22d2-466f-95ed-2dfd8b172c6d
KEYCLOAK_RSA_PUBLIC_KEY=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk0byyaYIH1kJPRVE74kFIjAp/9e5QXlcAwfIkNi4G/SMlYHWaN+ZoJVZJMG0SqNsivB+mVqxdxR2ZqPX4giQGFuYV7ySfJENmPjH1M9vH6P+Ddd1CfG4u44XVrQKLGV0EQKgIBB3a5rNmSo3vRncAbGzorqWsVJoqZzxCqOtHoTTvZwTix+apSpv/imz9R55aBcMjF1fwAwf0GANgIijIvddrMEGtJpMQ1tiP2uvf1lb0I5n91vyLqGFrsUdi+xYMGgqpcejIVNyYNpyxXllZFCa1ydmG6zseRa/FjCDDvzovXUcpkAliPDNqiIBLCiZ3Kgych3JvwxJBu1Hn0Sz+wIDAQAB
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
