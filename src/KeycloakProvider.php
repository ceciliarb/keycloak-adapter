<?php

namespace Prodabel\KeycloakAdapter;

use Stevenmaguire\OAuth2\Client\Provider\Keycloak;
use League\OAuth2\Client\Token\AccessToken;


class KeycloakProvider extends Keycloak
{
    public function getPermissions(AccessToken $token)
    {
        $params = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'audience'      => 'teste2_dsv',
            'response_mode' => 'permissions',
            // 'permission' => 'pagina1',
            "grant_type"    => "urn:ietf:params:oauth:grant-type:uma-ticket"
        ];
        $method  = $this->getAccessTokenMethod();
        $url     = $this->getAccessTokenUrl($params);
        $options = [
            'headers' => [
                'content-type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer ' . $token->getToken()
            ]
        ];
        $options['body'] = $this->getAccessTokenBody($params);
        $request  = $this->getRequest($method, $url, $options);
        $response = $this->getParsedResponse($request);
        $prepared = $this->prepareAccessTokenResponse($response);
        return collect($prepared)->unique()->keyBy('rsname')->keys()->all();

    }

}
