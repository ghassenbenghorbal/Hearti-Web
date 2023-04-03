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
use App\Http\Controllers\MessageController;

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
Route::prefix('v1')->middleware('auth')->group(function () {
    Route::prefix('chat')->group(function() {
        Route::get('discussions/{id}', [MessageController::class, 'getDiscussions'])->name('discussions');
        Route::get('messages/{sender}/{receiver}', [MessageController::class, 'getMessages'])->name('messages');
        Route::post('store', [MessageController::class, 'store'])->name('store');
        Route::get('{sender}/{receiver}', [MessageController::class, 'show'])->name('show');
        Route::delete('{id}', [MessageController::class, 'destroy'])->name('destroy');
        
    });
});
