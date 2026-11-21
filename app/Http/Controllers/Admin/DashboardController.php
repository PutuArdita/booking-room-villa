<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today()->toDateString();

        $stats = [
            'perlu_verifikasi' => Payment::where('status', Payment::PENDING)->count(),
            'menunggu_bayar' => Booking::where('status', Booking::PENDING)
                ->where('expires_at', '>', now())
                ->whereDoesntHave('payments', fn($q) => $q->where('status', Payment::PENDING))
                ->count(),
            'checkin_hari_ini' => Booking::where('status', Booking::CONFIRMED)
                ->whereDate('check_in', $today)->count(),
            'checkout_hari_ini' => Booking::where('status', Booking::CHECKED_IN)
                ->whereDate('check_out', $today)->count(),
            'menginap' => Booking::where('status', Booking::CHECKED_IN)->count(),
        ];

        $checkIns = Booking::with('rooms.roomType')
            ->where('status', Booking::CONFIRMED)
            ->whereDate('check_in', $today)
            ->get();

        $checkOuts = Booking::with('rooms.roomType')
            ->where('status', Booking::CHECKED_IN)
            ->whereDate('check_out', $today)
            ->get();

        return view('admin.dashboard', compact('stats', 'checkIns', 'checkOuts'));
    }
}
