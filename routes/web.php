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


Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');


Route::get('/', 'IndexController@index')->name('index');
//搜索
Route::get('/search', 'SearchController@index')->name('search');
//打点
Route::get('/search', 'SearchController@index')->name('search');
Route::get('/category/{category}', 'CategoryController@index')->name('category');
//这里是需要静态化的所有路由
Route::get('/merchant/{slug}/{merchant_id}', 'MerchantController@show')->name('merchant.detail');
Route::get('/merchants', 'MerchantController@index')->name('merchant.list');
Route::get('/category/{category}/merchants', 'CategoryController@merchants')->name('category.merchant.list');
Route::get('/mail/subscribe', 'MailController@subscribe')->name('mail.subscribe');


Route::group(['middleware' => ['page-cache']], function () {
});
Route::get('/sitemap', 'SitemapController@index');
//出站跳转
Route::get('/goto/out/{domain}', 'GotoController@out')->middleware('throttle:60,1')->name('out');
Route::get('/goto/product/{productId}', 'GotoController@productOut')->middleware('throttle:60,1')->name('product.out');


