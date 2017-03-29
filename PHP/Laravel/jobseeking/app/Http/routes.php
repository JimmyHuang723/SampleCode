<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('api/search', 'TokenAuthController@search');

Route::post('api/register', 'TokenAuthController@register');

Route::post('api/authenticate', 'TokenAuthController@authenticate');

Route::get('api/authenticate/user', 'TokenAuthController@getAuthenticatedUser');




Route::resource('user','UserController');