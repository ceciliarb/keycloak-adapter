<?php

namespace Prodabel\KeycloakAdapter;

use Stevenmaguire\OAuth2\Client\Provider\Keycloak;
use League\OAuth2\Client\Token\AccessToken;
use Firebase\JWT\JWT;


class KeycloakProvider extends Keycloak
{
    public function getPermissions(AccessToken $token)
    {
        $params = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'audience'      => $this->clientId,
            'response_mode' => 'permissions',
            'scope'         => "openid,profile,roles",
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

    public function getRoles(AccessToken $token)
    {
        $jwt = $this->getDecodedToken($token);
        $ret = [];
        $ret['realm_access']    = $jwt['realm_access'];
        $ret['resource_access'] = $jwt['resource_access'];
        return $ret;
    }

    public function getAud(AccessToken $token)
    {
        $jwt = $this->getDecodedToken($token);
        $ret = [];
        $ret['aud'] = $jwt['aud'];
        return $ret;
    }

    public function getDecodedToken(AccessToken $token)
    {
        $key = config('keycloak.rsa_public_key');
        $publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
$key
-----END PUBLIC KEY-----
EOD;

        return \json_decode(
            \json_encode(
                JWT::decode(
                    $token,
                    $publicKey,
                    array('RS256')
                )
            ),
            true
        );
    }

}
