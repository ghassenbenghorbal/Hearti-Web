<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Charts\HeartRateController;
use App\Http\Controllers\Charts\BloodPressureController;
use App\Http\Controllers\Charts\TemperatureController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('auth/login');
})->middleware('guest')->name('/');

Route::middleware(['auth'])->group(function () {

    Route::get('home', [HomeController::class, 'index'])->name('home');

    Route::prefix('heart-rate')->group(function(){
        Route::get('/{id}', [HeartRateController::class, 'index'])->name('heart-rate.index');
        Route::post('store', [HeartRateController::class, 'store'])->name('heart-rate.store');
    });

    Route::prefix('blood-pressure')->group(function(){
        Route::get('/{id}', [BloodPressureController::class, 'index'])->name('blood-pressure.index');
        Route::post('store', [BloodPressureController::class, 'store'])->name('blood-pressure.store');
    });

    Route::prefix('temperature')->group(function(){
        Route::get('/{id}', [TemperatureController::class, 'index'])->name('temperature.index');
        Route::post('store', [TemperatureController::class, 'store'])->name('temperature.store');
    });

    Route::prefix('profile')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('profile');
        Route::put('bracelet', [PatientController::class, 'updateBracelet'])->name('profile.bracelet');
        Route::put('change-password', [UserController::class, 'changePassword']);
        Route::put('change-profile', [UserController::class, 'changeProfile']);
        Route::post('generate-token', [UserController::class, 'generateApiToken']);
    });

    Route::prefix('patient')->group(function(){
        Route::get('/', [PatientController::class, 'index'])->name('patient.index');
        Route::post('store', [PatientController::class, 'store'])->name('patient.store');
        Route::put('update/{id}', [PatientController::class, 'update'])->name('patient.update');
        Route::delete('delete/{id}', [PatientController::class, 'destroy'])->name('patient.destroy');
    });

    Route::get('/analysis', function () {
        return Inertia::render('analysis');
    })->name('analysis');
    

    Route::prefix('chat')->group(function() {
        Route::get('/', function () {
            return Inertia::render('chat');
        })->name('chat');
        Route::get('discussions/{id}', [MessageController::class, 'getDiscussions'])->name('discussions');
        Route::prefix('messages')->group(function () {
            Route::get('{sender}/{receiver}', [MessageController::class, 'getMessages'])->name('messages');
            Route::post('store', [MessageController::class, 'store'])->name('messages.store');
        });
        Route::delete('{id}', [MessageController::class, 'destroy'])->name('destroy');        
    });

    Route::prefix('patient-users')->group(function () {
        Route::get('/', [PatientController::class, 'getPatientUsers'])->name('patient-users');
    });
});


require __DIR__.'/auth.php';
