<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\RoomType;
use Carbon\Carbon;

class ReportService
{
    public function monthly(Carbon $month): array
    {
        $start = $month->copy()->startOfMonth()->startOfDay();
        $end = $start->copy()->addMonth(); // awal bulan berikutnya (eksklusif)
        $days = $start->daysInMonth;

        $types = RoomType::withCount([
            'rooms as active_rooms_count' => fn($q) => $q->where('is_active', true),
        ])->orderBy('name')->get();

        $rows = [];
        foreach ($types as $type) {
            $rows[$type->id] = [
                'name' => $type->name,
                'bookings' => [],
                'nights' => 0,
                'revenue' => 0,
                'capacity' => $type->active_rooms_count * $days,
            ];
        }

        $bookings = Booking::with('rooms')
            ->whereIn('status', [Booking::CONFIRMED, Booking::CHECKED_IN, Booking::CHECKED_OUT])
            ->where('check_in', '<', $end->toDateString())
            ->where('check_out', '>', $start->toDateString())
            ->get();

        foreach ($bookings as $booking) {
            $from = $booking->check_in->copy()->max($start);
            $to = $booking->check_out->copy()->min($end);
            $nights = (int) $from->diffInDays($to);

            foreach ($booking->rooms as $room) {
                if (!isset($rows[$room->room_type_id])) {
                    continue;
                }

                $rows[$room->room_type_id]['bookings'][$booking->id] = true;
                $rows[$room->room_type_id]['nights'] += $nights;
                $rows[$room->room_type_id]['revenue'] += $nights * $room->pivot->price_per_night;
            }
        }

        $rows = collect($rows)->map(function ($row) {
            $row['booking_count'] = count($row['bookings']);
            $row['occupancy'] = $row['capacity'] > 0 ? $row['nights'] / $row['capacity'] * 100 : 0;
            $row['adr'] = $row['nights'] > 0 ? intdiv($row['revenue'], $row['nights']) : 0;

            return $row;
        })->values();

        $nights = $rows->sum('nights');
        $revenue = $rows->sum('revenue');
        $capacity = $rows->sum('capacity');

        $cancelled = Booking::where('status', Booking::CANCELLED)
            ->whereHas('payments', fn($q) => $q->where('status', Payment::VERIFIED))
            ->where('check_in', '<', $end->toDateString())
            ->where('check_out', '>', $start->toDateString())
            ->get(['id', 'total_price']);

        return [
            'rows' => $rows,
            'summary' => [
                'revenue' => $revenue,
                'bookings' => $bookings->count(),
                'nights' => $nights,
                'occupancy' => $capacity > 0 ? $nights / $capacity * 100 : 0,
                'adr' => $nights > 0 ? intdiv($revenue, $nights) : 0,
            ],
            'cancelled' => [
                'count' => $cancelled->count(),
                'amount' => $cancelled->sum('total_price'),
            ],
        ];
    }
}
