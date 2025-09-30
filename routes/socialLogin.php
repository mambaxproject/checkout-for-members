<?php

use Laravel\Socialite\Facades\Socialite;

Route::get('auth/redirect/{driver}', function ($driver) {
    return Socialite::driver($driver)->redirect();
})->name('social.redirect');

Route::get('/auth/callback/{driver}', 'Auth\LoginController@handleProviderCallbackSocialLogin')->name('social.callback');

