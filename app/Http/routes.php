<?php

    Route::get('/', 'HomeController@index');
    Route::post('/install', 'HomeController@install');

    Route::get('/test', [
        'middleware' => 'subscribed:monthly',
        function () {
            return 'Hey there';
        },
    ]);

    Route::auth();
