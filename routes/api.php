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
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', "AuthController@login");
    Route::post('signup', "AuthController@signup");
    Route::get('user-profile', "AuthController@userProfile");
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
});

Route::group(['middleware' => 'auth:api'], function () {
    // SERVICE API
    Route::get('services', "Api\ServiceController@index");
    Route::post('services', "Api\ServiceController@store");
    Route::get('services/{id}', "Api\ServiceController@show");
    Route::post('services/{id}', "Api\ServiceController@update");
    Route::delete('services/{id}', "Api\ServiceController@destroy");
    Route::get('get-services', "Api\ServiceController@getServices");

    // SERVICE LIST API
    Route::resource('service-list', "Api\ServiceListController");
    Route::post('service-list/{id}', "Api\ServiceListController@update");
    Route::get('get-service-list', "Api\ServiceListController@getServiceList");

    // PRODUCT API
    Route::resource('product', "Api\ProductController");

    // CATEGORY LIST API
    Route::resource('category', "Api\CategoryController");
    Route::post('category/{id}', "Api\CategoryController@update");
    Route::get('get-category-list', "Api\CategoryController@getServiceList");

    // SUB SERVICE LIST API
    Route::resource('sub-service-list', "Api\SubServiceController");
    Route::get('get-sub-service-list', "Api\SubServiceController@getSubService");

    // ORDER API
    Route::resource('orders', 'Api\OrderController');
    Route::get('bookings', 'Api\OrderController@bookings');
    Route::post('orders/{id}/payment', 'Api\OrderController@payment');

    //USERS
    Route::get('get-customers', 'Api\UserController@getCustomers');
    Route::post('users', 'Api\UserController@store');
    Route::post('users/change-password', 'Api\UserController@updatePassword');
    Route::post('users/{id}', 'Api\UserController@update');
    Route::get('get-sellers', 'Api\UserController@getSellers');
    Route::get('get-staffs', 'Api\UserController@getStaffs');

    // BANNER
    Route::resource('banners', "Api\BannerController");
    Route::post('banners/{id}', "Api\BannerController@update");

    // ABOUT
    Route::resource('about-us', "Api\AboutUsController");
    Route::resource('terms-of-service', "Api\TermsServiceController");
    Route::resource('feedbacks', "Api\FeedbackController");

    // SUB BANNER
    Route::resource('sub-banners', "Api\BannerController");
    Route::post('sub-banners/{id}', "Api\BannerController@update");

    // OFFER
    Route::resource('offers', "Api\OfferController");

    // PACKAGES
    Route::resource('packages', "Api\PackageController");
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
