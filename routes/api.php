<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(TripController::class)->group(function () {
    Route::get('/trips/{id}', 'show');
    Route::post('/trips', 'create');
    Route::put('/trips/{id}', 'update');
    Route::delete('/trips/{id}', 'delete');
    Route::get('/trips', 'all');
});

Route::controller(ReservationController::class)->prefix('reservations')->group(function () {
    Route::post('/', 'create');
    //Route::put('/', 'update');
    Route::delete('/{id}', 'delete');
    Route::get('/', 'all');
});

Route::controller(UserController::class)->prefix('reservations')->group(function () {
    Route::post('/', 'create');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'delete');
    Route::get('/', 'all');
});
