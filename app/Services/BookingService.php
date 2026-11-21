<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Mail\BookingCreated;

class BookingService
{
    public function __construct(
        private AvailabilityService $availability,
        private BookingMailer $mailer,
    ) {
    }

    public function create(Room $room, array $data): Booking
    {
        $checkIn = Carbon::parse($data['check_in'])->startOfDay();
        $checkOut = Carbon::parse($data['check_out'])->startOfDay();
        $nights = (int) $checkIn->diffInDays($checkOut);

        $booking = DB::transaction(function () use ($room, $data, $checkIn, $checkOut, $nights) {
            // Kunci baris kamar. Harus jadi query pertama di dalam transaksi.
            $locked = Room::whereKey($room->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            $available = $locked
                && $this->availability->availableRooms($checkIn, $checkOut)
                    ->whereKey($locked->id)
                    ->exists();

            if (!$available) {
                throw ValidationException::withMessages([
                    'room_id' => __('Kamar sudah tidak tersedia pada tanggal tersebut. Silakan pilih kamar lain.'),
                ]);
            }

            $type = $locked->roomType;

            if ($type->capacity < (int) $data['guests']) {
                throw ValidationException::withMessages([
                    'guests' => __('Jumlah tamu melebihi kapasitas kamar.'),
                ]);
            }

            $booking = Booking::create([
                'code' => $this->generateCode(),
                'guest_name' => $data['guest_name'],
                'guest_email' => $data['guest_email'],
                'guest_phone' => $data['guest_phone'],
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'guests_count' => (int) $data['guests'],
                'total_price' => $type->base_price * $nights,
                'status' => Booking::PENDING,
                'expires_at' => now()->addHours(config('villa.payment_hours')),
                'notes' => $data['notes'] ?? null,
                'locale' => app()->getLocale(),
            ]);

            $booking->rooms()->attach($locked->id, ['price_per_night' => $type->base_price]);

            $booking->statusLogs()->create([
                'from_status' => null,
                'to_status' => Booking::PENDING,
                'note' => 'Booking dibuat tamu',
            ]);

            return $booking;
        });
        $this->mailer->send($booking, new BookingCreated($booking));

        return $booking;
    }

    private function generateCode(): string
    {
        do {
            $code = 'VA' . strtoupper(Str::random(8));
        } while (Booking::where('code', $code)->exists());

        return $code;
    }
}
