<?php

namespace Prodabel\KeycloakAdapter;

use Illuminate\Contracts\Auth\Authenticatable;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;
use League\OAuth2\Client\Token\AccessToken;
use Illuminate\Http\Request;

class User implements Authenticatable
{
    private $kc_provider;
    protected $rememberTokenName = 'remember_token';
    protected $rolesName = 'roles_client';
    protected $sub;
    protected $email_verified;
    protected $name;
    protected $preferred_username;
    protected $given_name;
    protected $locale;
    protected $family_name;
    protected $email;
    protected $roles;
    protected $permissions;

    public function __construct(Keycloak $kc_provider, array $options=[])
    {
        $this->kc_provider = $kc_provider;
        if(!empty($options)) {
            if($options['rememberTokenName']) {
                $this->rememberTokenName = $options['rememberTokenName'];
            }
            if($options['rolesName']) {
                $this->rememberTokenName = $options['rolesName'];
            }
        }
    }

    public function fetchPermissions(?AccessToken $token)
    {
        if(!$token || !isset($this->kc_provider))
        {
            return null;
        }
        $permissions = null;
        $permissions = $this->kc_provider->getPermissions($token);

        return $permissions;
    }

    public function fetchUserByCredentials(array $credentials)
    {
        $this->fetchUserByToken($credentials['token']);
    }

    public function fetchUserByToken(?AccessToken $token)
    {
        if(!$token || !isset($this->kc_provider))
        {
            return null;
        }
        $arr_user    = null;
        $permissions = null;
        try {
            $arr_user    = $this->kc_provider->getResourceOwner($token);
            $permissions = $this->kc_provider->getPermissions($token);

        } catch(\Exception $e) {
            if(!str_contains($e->getMessage(), 'access_denied')) {
                try {
                    $token = $this->kc_provider->getAccessToken('refresh_token', ['refresh_token' => $token->getRefreshToken()]);
                } catch(\Exception $e) {
                    return null;
                }
            }

        }

        if($arr_user) {
            $arr_user = $arr_user->toArray();
            if($arr_user['sub'] == null)  {
                return null;
            }
            $this->sub                = $arr_user['sub'] ?? '';
            $this->email_verified     = $arr_user['email_verified'] ?? '';
            $this->name               = $arr_user['name'] ?? '';
            $this->preferred_username = $arr_user['preferred_username'] ?? '';
            $this->given_name         = $arr_user['given_name'] ?? '';
            $this->locale             = $arr_user['locale'] ?? '';
            $this->family_name        = $arr_user['family_name'] ?? '';
            $this->email              = $arr_user['email'] ?? '';
            $this->roles              = $arr_user[$this->rolesName] ?? [];
            $this->permissions        = $permissions ?? [];
        }
        return $this;
    }

    public function getAuthIdentifierName()
    {
        return "preferred_username";
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        if (!empty($this->getRememberTokenName())) {
            return $this->{$this->getRememberTokenName()};
        }
    }

    public function setRememberToken($value)
    {
        if (!empty($this->getRememberTokenName())) {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    public function getRememberTokenName()
    {
        return $this->rememberTokenName;
    }

    public function toArray()
    {
        return [
            'sub'                => $this->sub,
            'email_verified'     => $this->email_verified,
            'name'               => $this->name,
            'preferred_username' => $this->preferred_username,
            'given_name'         => $this->given_name,
            'locale'             => $this->locale,
            'family_name'        => $this->family_name,
            'email'              => $this->email,
            'roles'              => $this->roles,
            'permissions'        => $this->permissions,
        ];
    }

}
