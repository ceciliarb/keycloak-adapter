<?php

use Illuminate\Http\Request;


Route::group(['middleware' => ['web']], function () {
    Route::get('oi', function() {  return 'hello world!'; })->name('oi');

    Route::middleware('openid.login')->get('/login', function() {
        return 'login';
    })->name('login');


    Route::middleware('auth')->group(function() {
        Route::get('teste', function() {  return 'protected'; })->name('teste');
    });

    Route::get('logout', function (Request $request) {
        $Keycloak = resolve('Keycloak');
        $state = session('oauth2state');
        $request->session()->flush();
        Cookie::forget('access_token');
        Cookie::forget('refresh_token');
        Cookie::forget('expires');
        return redirect($Keycloak->getLogoutUrl(['redirect_uri' => config('keycloak.redirectLogoutUri'), 'state' => '']));
    })->name("logout");
});

