<x-mail::message>
# {{ __('Pembayaran Anda ditolak') }}

{{ __('Halo :name, bukti pembayaran Anda belum dapat kami terima.', ['name' => $booking->guest_name]) }}

- **{{ __('Kode booking') }}:** {{ $booking->code }}
- **{{ __('Alasan') }}:** {{ $reason }}

{{ __('Silakan unggah ulang bukti transfer sebelum :deadline WITA.', ['deadline' => $booking->expires_at->format('d/m/Y H:i')]) }}

<x-mail::button :url="route('booking.pay', $booking->code)">
{{ __('Bayar Sekarang') }}
</x-mail::button>

{{ config('villa.name') }}
</x-mail::message>
