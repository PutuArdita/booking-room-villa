<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BookingConfirmed extends Mailable
{
    public function __construct(public Booking $booking)
    {
        $this->booking->loadMissing('rooms.roomType');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Booking :code terkonfirmasi', ['code' => $this->booking->code]),
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.booking-confirmed');
    }
}
