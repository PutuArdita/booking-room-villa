<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['month' => ['nullable', 'date_format:Y-m']]);

        $start = Carbon::createFromFormat('Y-m-d', ($request->input('month') ?? now()->format('Y-m')) . '-01')
            ->startOfDay();
        $end = $start->copy()->endOfMonth()->startOfDay();

        $rooms = Room::with('roomType')
            ->where('is_active', true)
            ->orderBy('room_type_id')
            ->orderBy('name')
            ->get();

        $cells = []; // [room_id][Y-m-d] => ['state' => ..., 'booking' => ..., 'reason' => ...]

        $bookings = Booking::blocking()
            ->with('rooms')
            ->where('check_in', '<=', $end->toDateString())
            ->where('check_out', '>', $start->toDateString())
            ->get();

        foreach ($bookings as $booking) {
            $state = $booking->status === Booking::PENDING ? 'pending' : 'booked';
            $from = $booking->check_in->copy()->max($start);
            $to = $booking->check_out->copy()->subDay()->min($end);

            foreach (CarbonPeriod::create($from, $to) as $day) {
                foreach ($booking->rooms as $room) {
                    $cells[$room->id][$day->toDateString()] = ['state' => $state, 'booking' => $booking];
                }
            }
        }

        $blocks = BlockedDate::where('start_date', '<=', $end->toDateString())
            ->where('end_date', '>=', $start->toDateString())
            ->get();

        foreach ($blocks as $block) {
            $from = $block->start_date->copy()->max($start);
            $to = $block->end_date->copy()->min($end);

            foreach (CarbonPeriod::create($from, $to) as $day) {
                $cells[$block->room_id][$day->toDateString()] ??= [
                    'state' => 'blocked',
                    'booking' => null,
                    'reason' => $block->reason,
                ];
            }
        }

        $days = CarbonPeriod::create($start, $end);
        $prev = $start->copy()->subMonth()->format('Y-m');
        $next = $start->copy()->addMonth()->format('Y-m');

        return view('admin.calendar.index', compact('rooms', 'cells', 'days', 'start', 'prev', 'next'));
    }
}
