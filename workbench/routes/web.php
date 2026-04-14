<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('wordpress')->redirect();
});

Route::get('/auth/callback', function () {
    $user = Socialite::driver('wordpress')->user();

    return response()->json($user);
});
