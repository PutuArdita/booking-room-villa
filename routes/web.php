<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PublicRoomController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\BlockedDateController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;

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

    // role owner
    Route::middleware(['auth', 'role:owner'])->group(function () {
        Route::resource('pengguna', UserController::class)
            ->except(['show', 'destroy'])
            ->parameters(['pengguna' => 'user'])
            ->names('users');

        Route::patch('pengguna/{user}/status', [UserController::class, 'toggleActive'])->name('users.toggle');
        Route::get('laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('laporan/ekspor', [ReportController::class, 'export'])->name('reports.export');
    });

    Route::middleware(['auth', 'role:admin,owner'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        // user account
        Route::get('akun', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('akun/profil', [AccountController::class, 'updateProfile'])->name('account.profile');
        Route::put('akun/password', [AccountController::class, 'updatePassword'])->name('account.password');
        // Route::view('/', 'admin.dashboard')->name('dashboard');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('kalender', [CalendarController::class, 'index'])->name('calendar');
        Route::get('blokir-tanggal', [BlockedDateController::class, 'index'])->name('blocked-dates.index');
        Route::get('blokir-tanggal/buat', [BlockedDateController::class, 'create'])->name('blocked-dates.create');
        Route::post('blokir-tanggal', [BlockedDateController::class, 'store'])->name('blocked-dates.store');
        Route::delete('blokir-tanggal/{blockedDate}', [BlockedDateController::class, 'destroy'])->name('blocked-dates.destroy');
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


        Route::get('reservasi', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('reservasi/{booking}', [ReservationController::class, 'show'])->name('reservations.show');
        Route::patch('reservasi/{booking}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status');
        Route::post('pembayaran/{payment}/verifikasi', [ReservationController::class, 'verifyPayment'])->name('payments.verify');
        Route::post('pembayaran/{payment}/tolak', [ReservationController::class, 'rejectPayment'])->name('payments.reject');
        Route::get('pembayaran/{payment}/bukti', [ReservationController::class, 'proof'])->name('payments.proof');
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


// expiring booking
Schedule::command('bookings:expire')->everyFiveMinutes()->withoutOverlapping();
