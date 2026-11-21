@php($room = $booking->rooms->first())
<x-mail::message>
# {{ __('Booking Anda sudah dibuat') }}

{{ __('Halo :name, terima kasih telah memesan di :villa.', ['name' => $booking->guest_name, 'villa' => config('villa.name')]) }}

- **{{ __('Kode booking') }}:** {{ $booking->code }}
- **{{ __('Kamar') }}:** {{ $room?->roomType->name }} - {{ $room?->name }}
- **Check-in:** {{ $booking->check_in->format('d/m/Y') }}
- **Check-out:** {{ $booking->check_out->format('d/m/Y') }}
- **{{ __('Jumlah tamu') }}:** {{ $booking->guests_count }}
- **Total:** Rp {{ number_format($booking->total_price, 0, ',', '.') }}
- **{{ __('Bayar sebelum') }}:** {{ $booking->expires_at->format('d/m/Y H:i') }} WITA

**{{ __('Transfer ke rekening') }}:** {{ config('villa.bank.name') }} {{ config('villa.bank.account_number') }} ({{ config('villa.bank.account_holder') }})

<x-mail::button :url="route('booking.pay', $booking->code)">
{{ __('Bayar Sekarang') }}
</x-mail::button>

{{ config('villa.name') }}
</x-mail::message>
