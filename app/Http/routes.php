<?php

Route::get('/', ['middleware' => 'auth', 'uses' => 'HomeController@index']);
Route::post('/install', 'ShopController@requestPermissions');
Route::get('/shop/setup', 'ShopController@setup');

Route::auth();
