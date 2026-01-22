<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\FlightController;

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

    Route::prefix('flight')->name('admin.flight.')->controller(FlightController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/create', 'adminCreate')->name('create');
        Route::post('/', 'adminStore')->name('store');
        Route::get('/{flightId}/edit', 'adminEdit')->name('edit');
        Route::put('/{flightId}', 'adminUpdate')->name('update');
        Route::patch('/{flightId}', 'adminChangeStatus')->name('changeStatus');
        Route::delete('/{flightId}', 'adminDestroy')->name('destroy');
    });
});


require __DIR__.'/auth.php';
