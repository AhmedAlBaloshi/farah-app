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
    Route::post('login/{provider}', "AuthController@OAuth");
    Route::post('signup', "AuthController@signup");
    Route::post('forget-password', "AuthController@forgetPass");
    Route::post('reset-password', "AuthController@resetPass");
    Route::get('user-profile', "AuthController@userProfile");
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
});

Route::group(['middleware' => 'auth:api'], function () {

    // SERVICE API
    Route::post('services', "Api\ServiceController@store");
    Route::post('services/{id}', "Api\ServiceController@update");
    Route::delete('services/{id}', "Api\ServiceController@destroy");

    // SERVICE LIST API
    Route::post('service-list', "Api\ServiceListController@store");
    Route::post('service-list/{id}', "Api\ServiceListController@update");

    // PRODUCT API
    Route::post('product', "Api\ProductController@store");
    Route::post('product/{id}', "Api\ProductController@update");

    // CATEGORY LIST API
    Route::post('category', "Api\CategoryController@store");
    Route::post('category/{id}', "Api\CategoryController@update");

    // SUB SERVICE LIST API
    Route::post('sub-service-list', "Api\SubServiceController@store");
    Route::post('sub-service-list/{id}', "Api\SubServiceController@update");

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

    // FEEDBACK
    Route::resource('feedbacks', "Api\FeedbackController");
    // Rating
    Route::post('ratings', "Api\FeedbackController@rating");

    // SUB BANNER
    Route::resource('sub-banners', "Api\BannerController");
    Route::post('sub-banners/{id}', "Api\BannerController@update");

    // OFFER
    Route::post('offers', "Api\OfferController@store");
    Route::put('offers/{id}', "Api\OfferController@update");

    // PACKAGES
    Route::post('packages', "Api\PackageController@store");
    Route::post('packages/{id}', "Api\PackageController@update");
});
// SEARCH API
Route::get('search', "Api\BaseController@search");

// SUB SERVICE LIST API
Route::resource('sub-service-list', "Api\SubServiceController");
Route::get('get-sub-service-list', "Api\SubServiceController@getSubService");
Route::get('get-time-slots/{id}/{date}', "Api\SubServiceController@getTimeSlots");

// OFFER
Route::resource('offers', "Api\OfferController");


// SERVICE LIST API
Route::resource('service-list', "Api\ServiceListController");
Route::get('get-service-list', "Api\ServiceListController@getServiceList");

// SERVICE API
Route::get('services', "Api\ServiceController@index");
Route::get('services/{id}', "Api\ServiceController@show");
Route::get('get-services', "Api\ServiceController@getServices");

// PRODUCT API
Route::resource('product', "Api\ProductController");

// PACKAGES
Route::resource('packages', "Api\PackageController");

// CATEGORY LIST API
Route::resource('category', "Api\CategoryController");
Route::get('get-category-list', "Api\CategoryController@getServiceList");

// BANNER
Route::resource('banners', "Api\BannerController");
Route::post('banners/{id}', "Api\BannerController@update");

//ABOUT US
Route::resource('about-us', "Api\AboutUsController");
//TERMS OF SERVICES
Route::resource('terms-of-service', "Api\TermsServiceController");

//THAWANI PAYMENT
Route::get('thawani-pay/{order_id}', 'Api\ThawaniController@checkout');
Route::get('thawani-pay/success/{order_id}', 'Api\ThawaniController@success');
Route::get('thawani-pay/cancel/{order_id}', 'Api\ThawaniController@cancel');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
