<?php

namespace Prodabel\KeycloakAdapter;

use Illuminate\Support\ServiceProvider;

class KeycloakAdapterServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->publishes([
            __DIR__.'/../config/keycloak.php' => config_path('keycloak.php'),
        ]);
        $this->publishes([
            __DIR__.'/../config/auth.php' => config_path('keycloak_auth.php'),
        ]);
        $router = $this->app['router'];
        $router->aliasMiddleware('openid.login', \Prodabel\KeycloakAdapter\OpenIdLogin::class);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('Keycloak', function ($app) {
            return new KeycloakProvider([
                'authServerUrl' => config('keycloak.authServerUrl'),
                'realm' => config('keycloak.realm'),
                'clientId' => config('keycloak.clientId'),
                'clientSecret' => config('keycloak.clientSecret'),
                'redirectUri' => config('keycloak.redirectUri'),
            ]);
        });

        $this->app->bind('App\Models\Auth\User', function ($app) {
            return new User($app->make('Keycloak'));
        });

        // add custom guard provider
        \Auth::provider('kc_driver_provider', function ($app, array $config) {
            return new KeycloakUserProvider($app->make('App\Models\Auth\User'));
        });

        // add custom guard
        \Auth::extend('kc_driver_guard', function ($app, $name, array $config) {
            $userProvider = \Auth::createUserProvider('kc_users');
            return new KeycloakGuard($userProvider, $app->make('request'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['keycloakadapter'];
    }

}
