<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/webhook'], function(){

	Route::post('/line', 'WebhookController@line');

});

Route::group([
    'prefix' => '/schedule',
    'namespace' => 'Api'
], function ($route) {
    $route->get('/currency_record', 'ScheduleController@currencyRecord');
});

Route::group([
    'prefix' => '/currency',
    'namespace' => 'Api'
], function ($route) {
    $route->post('/searchBarInfo', 'CurrencyRecordController@getSearchBarInfo');
    $route->post('/highchartsInfo', 'CurrencyRecordController@getHighchartsInfo');
    $route->post('/caculate', 'CurrencyRecordController@getCaculateResult');
});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
