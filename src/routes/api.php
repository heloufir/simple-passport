<?php

Route::post('oauth/forgot-password', 'Heloufir\SimplePassport\Http\Controllers\PasswordController@forgot')
    ->name('simple-passport.password.forgot');

Route::put('oauth/recover-password/{token}', 'Heloufir\SimplePassport\Http\Controllers\PasswordController@recover')
    ->name('simple-passport.password.recover');