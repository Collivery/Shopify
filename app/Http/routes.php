<?php

    Route::get('/', ['middleware' => 'auth', 'uses' => 'HomeController@index']);
    Route::get('/install', ['middleware' => 'auth', 'uses' => 'HomeController@install']);

    Route::auth();
