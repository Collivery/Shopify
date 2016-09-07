<?php

Route::get('/', ['middleware' => 'auth', 'uses' => 'HomeController@index']);
Route::get('/install', ['middleware' => 'auth', 'uses' => 'HomeController@index']);
Route::post('/install', 'ShopController@requestPermissions');
Route::get('/shop/setup', 'ShopController@setup');

//webhook routes
Route::post('/service/customers/create', 'Hooks\WebhookController@customersCreate');
Route::post('/service/customers/update', 'Hooks\WebhookController@customersUpdate');
Route::post('/service/orders/create', 'Hooks\WebhookController@ordersCreate');
Route::post('/service/orders/paid', 'Hooks\WebhookController@ordersPaid');
Route::post('/service/app/uninstalled', 'Hooks\WebhookController@appUninstalled');
Route::post('/service/shop/update', 'Hooks\WebhookController@shopUpdate');

Route::get('/script/{town_id}/suburbs', 'Scripts\ScriptController@getSuburbs');
Route::get('/script/towns', 'Scripts\ScriptController@getTowns');
Route::get('/script/location-types', 'Scripts\ScriptController@getLocationTypes');

//shipping rates
Route::post('/service/shipping/rates', 'Hooks\WebhookController@rates');
Route::auth();
