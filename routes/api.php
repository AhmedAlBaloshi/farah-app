<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Auth
Route::post('login', "Api\UserController@login");
Route::post('signup', "Api\UserController@signup");
Route::get('user-profile', "Api\UserController@userProfile");

// SERVICE API
Route::get('services', "Api\ServiceController@index");
Route::post('services', "Api\ServiceController@store");
Route::get('services/{id}', "Api\ServiceController@show");
Route::post('services/{id}', "Api\ServiceController@update");
Route::delete('services/{id}', "Api\ServiceController@destroy");
Route::get('get-services', "Api\ServiceController@getServices");

// SERVICE LIST API
Route::resource('service-list', "Api\ServiceListController");
Route::get('get-service-list', "Api\ServiceListController@getServiceList");

// PRODUCT API
Route::resource('product', "Api\ProductController");

// CATEGORY LIST API
Route::resource('category', "Api\CategoryController");
Route::get('get-category-list', "Api\CategoryController@getServiceList");

// SUB SERVICE LIST API
Route::resource('sub-service-list', "Api\SubServiceController");
Route::get('get-sub-service-list', "Api\SubServiceController@getSubService");

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
