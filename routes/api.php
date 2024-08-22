<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarBrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarTypeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::controller(CarTypeController::class)->group(function () {
            Route::get('/car/types', 'index');
            Route::post('/car/types', 'store');
            Route::get('/car/types/{id}', 'show');
            Route::patch('/car/types/{id}', 'update');
            Route::delete('/car/types/{id}', 'delete');
        });
        Route::controller(CarBrandController::class)->group(function () {
            Route::get('/car-brands', 'index');
            Route::post('/car/brands', 'store');
            Route::get('/car/brands/{id}', 'show');
            Route::patch('/car/brands/{id}', 'update');
            Route::delete('/car/brands/{id}', 'delete');
        });
        Route::controller(CarController::class)->group(function () {
            Route::post('/car', 'store');
            Route::get('/car/{id}', 'show');
            Route::post('/car/update/{id}', 'update');
            Route::delete('/car/{id}', 'delete');
        });
    });
    Route::controller(CarController::class)->group(function () {
        Route::get('/car', 'index');
    });
    Route::controller(TransactionController::class)->group(function () {
        Route::get('/transactions', 'index');
        Route::post('/transactions', 'store');
        Route::get('/transactions/{id}', 'show');
        Route::get('/transactions/user', 'showByUser');
        Route::patch('/transactions/{id}', 'update');
        Route::post('/transactions/upload/{id}', 'uploadImage');
//        Route::delete('/car/{id}', 'delete');
    });
});




