<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire';

    protected $description = 'Tandai booking yang melewati batas waktu pembayaran sebagai kedaluwarsa';

    public function handle(): int
    {
        $expired = 0;

        Booking::query()
            ->where('status', Booking::PENDING)
            ->where('expires_at', '<=', now())
            ->whereDoesntHave('payments', fn($q) => $q->where('status', Payment::PENDING))
            ->pluck('id')
            ->each(function ($id) use (&$expired) {
                DB::transaction(function () use ($id, &$expired) {
                    $booking = Booking::whereKey($id)->lockForUpdate()->first();

                    // Periksa ulang setelah dikunci: tamu mungkin baru saja mengunggah bukti
                    if (
                        !$booking
                        || $booking->status !== Booking::PENDING
                        || $booking->expires_at->isFuture()
                        || $booking->payments()->where('status', Payment::PENDING)->exists()
                    ) {
                        return;
                    }

                    $booking->update(['status' => Booking::EXPIRED]);

                    $booking->statusLogs()->create([
                        'from_status' => Booking::PENDING,
                        'to_status' => Booking::EXPIRED,
                        'note' => 'Batas waktu pembayaran habis',
                    ]);

                    $expired++;
                });
            });

        $this->info("{$expired} booking ditandai kedaluwarsa.");

        return self::SUCCESS;
    }
}
