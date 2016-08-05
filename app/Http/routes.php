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

Route::get('/', 'HomeController@index');

Route::post('login', 'Auth\AuthController@login');

Route::get('logout', 'Auth\AuthController@logout');

Route::get('register', 'HomeController@register');

Route::post('register', 'Auth\AuthController@register');

Route::group(['middleware' => ['auth']], function() {
	Route::get('home', 'HomeController@home');				

	Route::get('tasks', 'TaskController@index');

	Route::post('addtask', 'TaskController@store');

	Route::delete('deletetask/{id}', 'TaskController@destroy');

	Route::put('updatetask/{id}', 'TaskController@update');

});