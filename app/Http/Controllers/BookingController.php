<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create(Request $request, AvailabilityService $availability)
    {
        $data = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $checkIn = Carbon::parse($data['check_in'])->startOfDay();
        $checkOut = Carbon::parse($data['check_out'])->startOfDay();

        $room = $availability->availableRooms($checkIn, $checkOut)
            ->with('roomType')
            ->find($data['room_id']);

        if (!$room || $room->roomType->capacity < (int) $data['guests']) {
            return redirect()
                ->route('availability.index', $request->only('check_in', 'check_out', 'guests'))
                ->with('error', __('Kamar tidak tersedia untuk pilihan tersebut.'));
        }

        $nights = (int) $checkIn->diffInDays($checkOut);
        $total = $room->roomType->base_price * $nights;

        return view('booking.create', compact('room', 'data', 'nights', 'total'));
    }

    public function store(StoreBookingRequest $request, BookingService $bookings)
    {
        $room = Room::findOrFail($request->validated('room_id'));

        $booking = $bookings->create($room, $request->validated());

        return redirect()
            ->route('booking.show', $booking->code)
            ->with('success', __('Booking dibuat. Selesaikan pembayaran sebelum batas waktu.'));
    }

    public function show(string $code)
    {
        // $booking = Booking::with('rooms.roomType')->where('code', $code)->firstOrFail();
        $booking = Booking::with('rooms.roomType', 'payments')->where('code', $code)->firstOrFail();

        return view('booking.show', compact('booking'));
    }
}
