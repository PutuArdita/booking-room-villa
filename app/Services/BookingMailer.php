<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class BookingMailer
{
    public function send(Booking $booking, Mailable $mailable): void
    {
        try {
            Mail::to($booking->guest_email)
                ->locale($booking->locale)
                ->send($mailable);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
