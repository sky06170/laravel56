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

Route::group(['prefix' => '/gtm'], function(){

	Route::get('/', 'GoogleGtmController@index');

});

Route::group(['prefix' => '/embed'], function(){

	Route::get('/youtube', 'YoutubeController@index');

});

Route::group(['prefix' => '/test'], function () {

	Route::get('/channelEvent', function(){
		event(new App\Events\PushMessage());
    	return "Event has been sent!";
	});

	Route::post('/channelSendMessage', 'TestController@channelSendMessage');

	Route::get('/channelListen/{name}', 'TestController@channelShow');

	Route::get('/showJuksyBannerList', 'TestController@showJuksyBannerList');

	Route::get('/sendCarouselBtnTemplate', 'TestController@sendCarouselBtnTemplate');

	Route::get('/sendCarouselImgTemplate', 'TestController@sendCarouselImgTemplate');

	Route::get('/sodiumCryptoBox', 'TestController@sodiumCryptoBox');

	Route::get('/sodiumCryptoBoxOpen/{encrypted}', 'TestController@sodiumCryptoBoxOpen');

});

Route::group(['prefix' => '/line'], function(){

	Route::get('/', 'LineController@index');

	Route::post('/sendText', 'LineController@sendText');

	Route::post('/sendImage', 'LineController@sendImage');

	Route::post('/sendButtonTemplate', 'LineController@sendButtonTemplate');

	Route::post('/sendConfirmTemplate', 'LineController@sendConfirmTemplate');

	Route::post('/sendCarouselBtnTemplate', 'LineController@sendCarouselBtnTemplate');

	Route::post('/sendCarouselImgTemplate', 'LineController@sendCarouselImgTemplate');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
