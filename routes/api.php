<?php

Route::group([

    'middleware' => 'api'

], function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('signup', 'AuthController@signup')->name('signup');
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('refresh', 'AuthController@refresh')->name('refresh');
    Route::post('me', 'AuthController@me')->name('me');
    Route::post('verify/email', 'VerificationController@sendEmail')->name('verify.email');


    Route::resource('videos', 'VideoController');
    Route::resource('profiles', 'ProfileController');
});
