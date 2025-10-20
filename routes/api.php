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


Route::middleware('auth:web,sanctum')
    ->prefix('v1')
    ->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/sessions', [\App\Http\Controllers\Api\v1\UserSessionController::class, 'index']);
        Route::get('/sessions/current', [\App\Http\Controllers\Api\v1\UserSessionController::class, 'show']);
        Route::delete('/sessions/{sessionId}', [\App\Http\Controllers\Api\v1\UserSessionController::class, 'destroy']);
        Route::delete('/sessions', [\App\Http\Controllers\Api\v1\UserSessionController::class, 'destroyOthers']);
    });
