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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//订阅api
Route::post('subscribe', 'Api\SubscribeController@index')->name('api.subscribe');
//用户
Route::post('user-tracks', 'Api\UserTrackController@index')->name('api.user-track');
//商品详情
Route::get('product-detail', 'Api\ProductController@show')->name('api.product-detail');

