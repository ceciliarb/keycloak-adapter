<?php

use Illuminate\Http\Request;

Route::group(['middleware' => ['web']], function () {
    Route::get('oi', function() {  return 'hello world!'; })->name('oi');

    Route::middleware('openid.login')->get('/login', function() {
        return 'login';
    })->name('login');


    Route::middleware('auth')->group(function() {
        Route::get('teste', function() {  return 'rota protegida / usuário autenticado'; })->name('teste');

        Route::get('infousu', function() {  return Auth::user()->toArray(); })->name('infousu');

        Route::get('logout', function (Request $request) {
            $Keycloak = resolve('Keycloak');
            $state = session('oauth2state');
            $request->session()->flush();
            Cookie::forget('token');
            return redirect($Keycloak->getLogoutUrl(['redirect_uri' => config('keycloak.redirectLogoutUri'), 'state' => $state]));
        })->name("logout");
    });

});


