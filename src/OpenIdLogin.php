<?php

namespace Prodabel\KeycloakAdapter;

use Closure;
use Illuminate\Support\Facades\Cookie;

class OpenIdLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $kc_provider = resolve('Keycloak');

        // If we don't have an authorization code then get one
        if ($request->isMethod('GET') && !isset($request->code)) {
            $request->session()->flush();
            $authUrl = $kc_provider->getAuthorizationUrl();
            session(['oauth2state' => $kc_provider->getState()]);
            return redirect($authUrl);

        // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($request->state) || ($request->state !== session('oauth2state'))) {
            $request->session()->forget('oauth2state');
            echo('Invalid state, make sure HTTP sessions are enabled.');
            return redirect('/login');

        // Try to get an access token (using the authorization code grant)
        } else {
            try {
                $token = $kc_provider->getAccessToken('authorization_code', [ "code" => $request->code ]);
                Cookie::queue('access_token' , $token->getToken(), 100, null, null, false, true);
                Cookie::queue('refresh_token', $token->getRefreshToken(), 100, null, null, false, true);
                Cookie::queue('expires'      , $token->getExpires(), 100, null, null, false, true);
                return redirect('/home');

            } catch (\Exception $e) {
                exit('Failed to get access token: '.$e->getMessage());
            }
        }
        return $next($request);
    }
}
