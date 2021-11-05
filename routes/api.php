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

Route::group(['prefix' => 'admin'], function() {
    // Sign up
    Route::post('/sign-up', 'Api\AuthController@signup');
    // login
    Route::post('/login', 'Api\AuthController@login');

    // Start Categories
        Route::group(['prefix' => 'categories'], function() {
            Route::get('/', 'Api\CategoryController@index');
            Route::post('/', 'Api\CategoryController@store');
            Route::get('/{category}', 'Api\CategoryController@show');
            Route::post('/{category}', 'Api\CategoryController@edit');
            Route::delete('/{category}', 'Api\CategoryController@destroy');
        });
    // End Categories

});
