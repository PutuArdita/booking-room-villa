<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PublicRoomController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('kamar', [PublicRoomController::class, 'index'])->name('rooms.index');
Route::get('kamar/{roomType:slug}', [PublicRoomController::class, 'show'])->name('rooms.show');
Route::get('bahasa/{locale}', [LocaleController::class, 'switch'])->name('locale');


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
        Route::resource('kamar', RoomController::class)
            ->except('show')
            ->parameters(['kamar' => 'room'])
            ->names('rooms');
        Route::post('tipe-kamar/{roomType}/foto', [RoomImageController::class, 'store'])
            ->name('room-types.images.store');
        Route::delete('foto/{image}', [RoomImageController::class, 'destroy'])
            ->name('room-images.destroy');
    });
});

// AVAILABILITY
Route::get('ketersediaan', [AvailabilityController::class, 'index'])->name('availability.index');

// BOOKING

Route::get('booking/buat', [BookingController::class, 'create'])->name('booking.create');
Route::post('booking', [BookingController::class, 'store'])
    ->middleware('throttle:10,1')->name('booking.store');
Route::get('booking/{code}', [BookingController::class, 'show'])->name('booking.show');

use App\Http\Controllers\PaymentController;

Route::get('booking/{code}/bayar', [PaymentController::class, 'show'])->name('booking.pay');
Route::post('booking/{code}/bayar', [PaymentController::class, 'store'])
    ->middleware('throttle:10,1')->name('booking.pay.store');
