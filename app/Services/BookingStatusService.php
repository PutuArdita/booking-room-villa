<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingStatusService
{
    private const TRANSITIONS = [
        Booking::PENDING => [Booking::CANCELLED],
        Booking::CONFIRMED => [Booking::CHECKED_IN, Booking::CANCELLED],
        Booking::CHECKED_IN => [Booking::CHECKED_OUT],
    ];

    public function allowed(Booking $booking): array
    {
        return self::TRANSITIONS[$booking->status] ?? [];
    }

    public function transition(Booking $booking, string $to, User $user, ?string $note = null): void
    {
        DB::transaction(function () use ($booking, $to, $user, $note) {
            $locked = Booking::whereKey($booking->id)->lockForUpdate()->firstOrFail();

            if (!in_array($to, $this->allowed($locked), true)) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status tidak diizinkan dari status saat ini.',
                ]);
            }

            if ($to === Booking::CANCELLED) {
                // Bukti yang belum diproses ikut ditutup supaya tidak menggantung
                $locked->payments()->where('status', Payment::PENDING)->update([
                    'status' => Payment::REJECTED,
                    'note' => 'Booking dibatalkan',
                    'verified_by' => $user->id,
                    'verified_at' => now(),
                ]);
            }

            $this->apply($locked, $to, $user, $note);
        });
    }

    public function verifyPayment(Payment $payment, User $user): void
    {
        DB::transaction(function () use ($payment, $user) {
            [$booking, $locked] = $this->lockForPayment($payment);

            $locked->update([
                'status' => Payment::VERIFIED,
                'verified_by' => $user->id,
                'verified_at' => now(),
            ]);

            $this->apply($booking, Booking::CONFIRMED, $user, 'Pembayaran diverifikasi');
        });
    }

    public function rejectPayment(Payment $payment, User $user, string $note): void
    {
        DB::transaction(function () use ($payment, $user, $note) {
            [$booking, $locked] = $this->lockForPayment($payment);

            $locked->update([
                'status' => Payment::REJECTED,
                'note' => $note,
                'verified_by' => $user->id,
                'verified_at' => now(),
            ]);

            // Beri waktu bayar ulang. Aman, karena kamar masih terkunci selama bukti menunggu.
            $booking->update(['expires_at' => now()->addHours(config('villa.payment_hours'))]);

            $this->log($booking, $booking->status, $booking->status, $user, 'Pembayaran ditolak: ' . $note);
        });
    }

    private function lockForPayment(Payment $payment): array
    {
        $booking = Booking::whereKey($payment->booking_id)->lockForUpdate()->firstOrFail();
        $locked = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();

        if ($locked->status !== Payment::PENDING || $booking->status !== Booking::PENDING) {
            throw ValidationException::withMessages([
                'payment' => 'Pembayaran ini sudah diproses, atau booking tidak lagi menunggu pembayaran.',
            ]);
        }

        return [$booking, $locked];
    }

    private function apply(Booking $booking, string $to, User $user, ?string $note): void
    {
        $from = $booking->status;
        $booking->update(['status' => $to]);
        $this->log($booking, $from, $to, $user, $note);
    }

    private function log(Booking $booking, ?string $from, string $to, User $user, ?string $note): void
    {
        $booking->statusLogs()->create([
            'from_status' => $from,
            'to_status' => $to,
            'user_id' => $user->id,
            'note' => $note,
        ]);
    }
}
