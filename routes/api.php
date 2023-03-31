<?php

use App\Http\Controllers\Api\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Charts\Co2Controller;
use App\Http\Controllers\Charts\HumidityController;
use App\Http\Controllers\Charts\TemperatureController;
use App\Http\Controllers\Charts\MovementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HospitalController;

use App\Http\Controllers\Charts\HeartRateController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::get('patient', [PatientController::class, 'index']);
});
Route::prefix('health')->group(function() {
    //Chart Routes
    Route::resource('co2', Co2Controller::class)->only(['index', 'store']);
    Route::resource('humidity', HumidityController::class)->only(['index', 'store']);
    Route::resource('temperature', TemperatureController::class)->only(['index', 'store']);
    Route::resource('movement', MovementController::class)->only(['index', 'store']);

    //Card Routes
    Route::prefix('heart-rate')->group(function() {
        Route::get('get-heart-rate', [HeartRateController::class, 'index'])->name("getHeartRate");
        Route::post('set-heart-rate', [HeartRateController::class, 'store'])->name("setHeartRate");
    });

});
