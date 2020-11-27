<?php

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
Route::middleware('accept.json')->group(function () {
    Route::post('token', 'Api\TokenController@update');

    Route::middleware('auth:api')->group(function () {
        Route::delete('token', 'Api\TokenController@delete');

        Route::apiResource('currency', 'Api\CurrencyController')
            ->only(['index', 'show', 'destroy']);
        Route::apiResource('currency.rate', 'Api\CurrencyRateController')
            ->only(['index', 'show', 'destroy']);
    });
});
