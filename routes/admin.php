<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AirportController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::prefix('user')->name('admin.user.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{userId}/edit', 'edit')->name('edit');
        Route::put('/{userId}', 'update')->name('update');
        Route::delete('/{userId}', 'destroy')->name('destroy');
    });

    Route::prefix('airport')->name('admin.airport.')->controller(AirportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{airportId}/edit', 'edit')->name('edit');
        Route::put('/{airportId}', 'update')->name('update');
        Route::delete('/{airportId}', 'destroy')->name('destroy');
    });

});


require __DIR__.'/auth.php';
