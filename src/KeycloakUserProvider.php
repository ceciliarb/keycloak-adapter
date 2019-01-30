<?php

namespace Prodabel\KeycloakAdapter;

use Illuminate\Contracts\Auth\UserProvider;
use Prodabel\KeycloakAdapter\User;
use Illuminate\Contracts\Auth\Authenticatable;


class KeycloakUserProvider implements UserProvider
{
    private $model;

    public function __construct(User $userModel)
    {
        $this->model = $userModel;
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }
        $user = $this->model->fetchUserByCredentials($credentials);
        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials) { }

    public function retrieveById($identifier) { }

    public function retrieveByToken($identifier=0, $token) {
        if (empty($token)) {
            return;
        }
        $user = $this->model->fetchUserByToken($token);
        return $user;
     }

    public function updateRememberToken(Authenticatable $user, $token) { }
}
