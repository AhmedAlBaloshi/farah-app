<?php

use App\Http\Controllers\Api\ServiceController;
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

Route::get('test', function () {
    return response()->json([
        'success' => 1,
        'message' => 'testing',
    ]);
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => 'service'], function () {
    Route::get('/', 'Api/ServiceController@get_service');
    Route::get('create', ['as' => 'service_create', 'uses' => 'Api/ServiceController@create']);
    Route::post('store', ['as' => 'service_store', 'uses' => 'Api/ServiceController@store']);
    Route::get('{id}/edit', ['as' => 'service_edit', 'uses' => 'Api/ServiceController@edit']);
    Route::patch('{id}/update', ['as' => 'service_update', 'uses' => 'Api/ServiceController@update']);
    Route::post('delete/{id}', ['as' => 'service_delete', 'uses' => 'Api/ServiceController@destroy']);
});
