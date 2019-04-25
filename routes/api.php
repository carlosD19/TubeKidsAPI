<?php

Route::group([

    'middleware' => 'api'

], function () {
    //USER
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('signup', 'AuthController@signup')->name('signup');
    Route::post('logout', 'AuthController@logout')->name('logout');
    //VERIFICATION EMAIL
    Route::post('verify/email', 'VerificationController@sendEmail')->name('verify.email');
    Route::get('confirm/email/{token}', 'VerificationController@confirmEmail')->name('confirm.email');
    //VERIFICATION CODE
    Route::post('verify/code', 'CodeVerificationController@verifySMS')->name('verify.code');
    Route::get('code/{email}', 'CodeVerificationController@sendCode')->name('send.code');
    //CRUD
    Route::resource('videos', 'VideoController');
    Route::post('videos/{video}', 'VideoController@update');
    Route::resource('profiles', 'ProfileController');
    //EXTRAS
    Route::post('refresh', 'AuthController@refresh')->name('refresh');
    Route::post('me', 'AuthController@me')->name('me');
});
