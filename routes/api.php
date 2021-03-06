<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
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
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout',[AuthController::class,'logout']);
    Route::post('addBalance',[BalanceController::class,'addBalance']);
    Route::post('createOrder',[OrderController::class,'createOrder']);
    Route::get('getServices',[ServiceController::class,'getServices']);
    Route::get('user',[AuthController::class,'user']);
    Route::get('getCars',[CarController::class,'getCars']);
    Route::get('getOrders',[OrderController::class,'getOrders']);
});

