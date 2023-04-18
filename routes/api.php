<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Charts\Co2Controller;
use App\Http\Controllers\Charts\HumidityController;
use App\Http\Controllers\Charts\TemperatureController;
use App\Http\Controllers\Charts\MovementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HospitalController;

use App\Http\Controllers\PatientController;
use App\Http\Controllers\Charts\HeartRateController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Api\Auth\ApiAuthController;

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

Route::prefix('auth')->group(function () {
    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('register', [ApiAuthController::class, 'register']);
    Route::post('logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('refresh', [ApiAuthController::class, 'refresh'])->middleware('auth:sanctum');
    Route::get('user', [ApiAuthController::class, 'user'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('chat')->group(function() {
        Route::get('discussions/{id}', [MessageController::class, 'getDiscussions']);
        Route::prefix('messages')->group(function () {
            Route::get('{sender}/{receiver}', [MessageController::class, 'getMessages']);
            Route::post('store', [MessageController::class, 'store']);
        });
        Route::delete('{id}', [MessageController::class, 'destroy']);        
    });

    Route::prefix('patient-users')->group(function () {
        Route::get('/', [PatientController::class, 'getPatientUsers']);
    });
});
