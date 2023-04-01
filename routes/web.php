<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
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
    Route::prefix('profile')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('profile');
        Route::put('change-password', [UserController::class, 'changePassword']);
        Route::put('change-profile', [UserController::class, 'changeProfile']);
        Route::post('generate-token', [UserController::class, 'generateApiToken']);
    });

    Route::prefix('patient')->group(function(){
        Route::get('/', [PatientController::class, 'index'])->name('patient.index');
        Route::post('store', [PatientController::class, 'store'])->name('patient.store');
        Route::put('update', [PatientController::class, 'update'])->name('patient.update');
        Route::delete('delete', [PatientController::class, 'destroy'])->name('patient.destroy');
    });

    Route::get('/analysis', function () {
        return Inertia::render('analysis');
    })->name('analysis');
    
    Route::get('chat', function () {
        return Inertia::render('chat');
    })->name('chat');
});


require __DIR__.'/auth.php';
