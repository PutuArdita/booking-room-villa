<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoomTypeController;

Route::get('/', function () {
    return view('welcome');
});


// ADMIN & OWNER AUTH
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1');
    });

    Route::middleware(['auth', 'role:admin,owner'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::view('/', 'admin.dashboard')->name('dashboard');
        // ROOM
        Route::resource('tipe-kamar', RoomTypeController::class)
            ->except('show')
            ->parameters(['tipe-kamar' => 'roomType'])
            ->names('room-types');
    });
});
