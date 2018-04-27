<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/test'], function(){

	Route::get('/', 'TestController@test');

	Route::get('/sodiumCryptoBox', 'TestController@sodiumCryptoBox');

	Route::get('/sodiumCryptoBoxOpen/{encrypted}', 'TestController@sodiumCryptoBoxOpen');

});

Route::group(['prefix' => '/line'], function(){

	Route::get('/', 'LineController@index');

	Route::post('/sendText', 'LineController@sendText');

	Route::post('/sendImage', 'LineController@sendImage');

	Route::post('/sendButtonTemplate', 'LineController@sendButtonTemplate');

	Route::post('/sendConfirmTemplate', 'LineController@sendConfirmTemplate');

	Route::post('/sendCarouselTemplate', 'LineController@sendCarouselTemplate');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
