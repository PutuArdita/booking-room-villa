<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PaymentRejected extends Mailable
{
    public function __construct(public Booking $booking, public string $reason)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Pembayaran booking :code ditolak', ['code' => $this->booking->code]),
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.payment-rejected');
    }
}
