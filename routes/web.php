<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return view('dashboard');
    });

    // Service Routes
    Route::group(['prefix' => 'service'], function () {
        Route::get('/', ['as' => 'service_index', 'uses' => 'ServiceController@index']);
        Route::get('create', ['as' => 'service_create', 'uses' => 'ServiceController@create']);
        Route::post('store', ['as' => 'service_store', 'uses' => 'ServiceController@store']);
        Route::get('{id}/edit', ['as' => 'service_edit', 'uses' => 'ServiceController@edit']);
        Route::patch('{id}/update', ['as' => 'service_update', 'uses' => 'ServiceController@update']);
        Route::post('delete/{id}', ['as' => 'service_delete', 'uses' => 'ServiceController@destroy']);
    });

    // Service List Routes
    Route::group(['prefix' => 'service-list'], function () {
        Route::get('/', ['as' => 'service_list_index', 'uses' => 'ServiceListController@index']);
        Route::get('create', ['as' => 'service_list_create', 'uses' => 'ServiceListController@create']);
        Route::post('store', ['as' => 'service_list_store', 'uses' => 'ServiceListController@store']);
        Route::get('{id}/edit', ['as' => 'service_list_edit', 'uses' => 'ServiceListController@edit']);
        Route::patch('{id}/update', ['as' => 'service_list_update', 'uses' => 'ServiceListController@update']);
        Route::post('delete/{id}', ['as' => 'service_list_delete', 'uses' => 'ServiceListController@destroy']);
    });

    // Sub Service List Routes
    Route::group(['prefix' => 'sub-service-list'], function () {
        Route::get('/', ['as' => 'sub_service_index', 'uses' => 'SubServiceController@index']);
        Route::get('create', ['as' => 'sub_service_create', 'uses' => 'SubServiceController@create']);
        Route::post('store', ['as' => 'sub_service_store', 'uses' => 'SubServiceController@store']);
        Route::get('{id}/edit', ['as' => 'sub_service_edit', 'uses' => 'SubServiceController@edit']);
        Route::patch('{id}/update', ['as' => 'sub_service_update', 'uses' => 'SubServiceController@update']);
        Route::post('delete/{id}', ['as' => 'sub_service_delete', 'uses' => 'SubServiceController@destroy']);
    });

    // Category Routes
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', ['as' => 'category_index', 'uses' => 'CategoryController@index']);
        Route::get('create', ['as' => 'category_create', 'uses' => 'CategoryController@create']);
        Route::post('store', ['as' => 'category_store', 'uses' => 'CategoryController@store']);
        Route::get('{id}/edit', ['as' => 'category_edit', 'uses' => 'CategoryController@edit']);
        Route::patch('{id}/update', ['as' => 'category_update', 'uses' => 'CategoryController@update']);
        Route::post('delete/{id}', ['as' => 'category_delete', 'uses' => 'CategoryController@destroy']);
    });

    // Users Routes
    Route::get('users', 'UserController@index')->name('users.index');
    Route::get('users/create', 'UserController@create')->name('users.create');
    Route::post('users', 'UserController@store')->name('users.store');
    Route::get('users/{id}', 'UserController@edit')->name('users.edit');
    Route::put('users/{id}', 'UserController@update')->name('users.update');
    Route::post('users/{id}', 'UserController@destroy')->name('users.destroy');

    // Orders Route
    Route::get('orders', 'OrderController@orders')->name('orders.index');
    Route::get('orders/{id}', 'OrderController@view')->name('orders.show');
    Route::post('orders/status/{id}', 'OrderController@updateStatus')->name('orders.update-status');

    // Bookings Route
    Route::get('bookings', 'BookingController@bookings')->name('bookings.index');
    Route::get('bookings/{id}', 'BookingController@view')->name('bookings.show');

    // Offer Routes
    Route::resource('offers', 'OfferController');

    // Pacakage Routes
    Route::resource('packages', 'PackageController');

    // Banner Routes
    Route::resource('banners', 'BannerController');

    // Feedback Routes
    Route::get('feedbacks', 'FeedbackController@index')->name('feedback.index');
    // About Routes
    Route::get('about-us', 'AboutUsController@index')->name('about.index');
    Route::put('about-us/{id}', 'AboutUsController@update')->name('about.update');

    // Payment Routes
    Route::get('payments', 'PaymentController@index')->name('payments.index');

    // term of services Routes
    Route::get('term-of-services', 'TermServiceController@index')->name('term.index');
    Route::put('term-of-services/{id}', 'TermServiceController@update')->name('term.update');

    // Product Routes
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', ['as' => 'product_index', 'uses' => 'ProductController@index']);
        Route::get('create', ['as' => 'product_create', 'uses' => 'ProductController@create']);
        Route::post('store', ['as' => 'product_store', 'uses' => 'ProductController@store']);
        Route::get('{id}/edit', ['as' => 'product_edit', 'uses' => 'ProductController@edit']);
        Route::patch('{id}/update', ['as' => 'product_update', 'uses' => 'ProductController@update']);
        Route::post('delete/{id}', ['as' => 'product_delete', 'uses' => 'ProductController@destroy']);
    });

    //ajax service list
    Route::get('/get_service_list', ['as' => 'get_service_list', 'uses' => 'GeneralController@getServiceList']);
    Route::get('/get_sub_service_list', ['as' => 'get_sub_service_list', 'uses' => 'GeneralController@getSubServiceList']);
});

Route::get('/home', 'HomeController@index');

Route::get('/test', 'TestController@index');
