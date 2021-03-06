<?php

namespace Prodabel\KeycloakAdapter;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Http\Request;
use League\OAuth2\Client\Token\AccessToken;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Contracts\Auth\UserProvider;


class KeycloakGuard implements Guard
{
    use GuardHelpers;

    private $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }
        $user = null;
		// retrieve via token
        $token = $this->getTokenForRequest();
        if (!empty($token)) {
			// the token was found, how you want to pass?
            $user = $this->provider->retrieveByToken(0, $token);
        }
        return $this->user = $user;
    }

    public function getTokenForRequest()
    {
        $token_bearer  = $this->request->bearerToken();
        $token_session = session('openid_token');
        $token_cookie  = $this->request->cookie('token');

        if($token_bearer) {
            $token = new AccessToken(['access_token' => $token_bearer]);

        } else if($token_session) {
            $token = $token_session;

        } else if($token_cookie) {
                $token = $this->request->cookie('token');
                $token = new AccessToken(['access_token' => $token]);

        } else {
            return null;
        }
        return $token;
    }

    public function validate(array $credentials = [])
    {
        if (empty($credentials) || empty($credentials['token'])) {
            return false;
        }
        if ($this->provider->retrieveByCredentials($credentials)) {
            return true;
        }
        return false;
    }
}