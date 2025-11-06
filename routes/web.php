<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\FrontpageController;
use App\Http\Controllers\ProfileController;

Route::get('/', [FrontpageController::class, 'index'])->name('frontpage');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'role:staff')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('flight')->name('flight.')->controller(FlightController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{flightId}/edit', 'edit')->name('edit');
        Route::put('/{flightId}', 'update')->name('update');
        Route::patch('/{flightId}', 'changeStatus')->name('changeStatus');
        Route::delete('/{flightId}', 'destroy')->name('destroy');
    });
});


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';