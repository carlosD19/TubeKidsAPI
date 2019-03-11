<?php

Route::group([

    'middleware' => 'basic.auth'

], function () {
	Route::get('login', function () {
    	return 'welcome';
	});
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
