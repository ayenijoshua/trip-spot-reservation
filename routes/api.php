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

//Route::put('/trips/update/{id}', [TripController::class,'update']);

Route::controller(TripController::class)->prefix('trips')->group(function () {
    Route::get('/{id}', 'show');
    Route::post('/', 'create');
    Route::put('{id}', 'update');
    Route::delete('/{id}', 'delete');
    Route::get('/', 'all');
    Route::get('/{id}/total-reservations', 'totalReservations');
    Route::get('/{id}/reservations', 'reservations');
});

Route::controller(ReservationController::class)->prefix('reservations')->group(function () {
    Route::post('/', 'create');
    Route::put('/canclation', 'cancle');
    Route::delete('/{id}', 'delete');
    Route::get('/', 'all');
    Route::get('/{id}', 'show');
});

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::post('/', 'create');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'delete');
    Route::get('/', 'all');
    Route::get('/{id}', 'show');
    Route::get('/{id}/total-reservations', 'totalReservations');
    Route::get('/{id}/reservations', 'reservations');
});
