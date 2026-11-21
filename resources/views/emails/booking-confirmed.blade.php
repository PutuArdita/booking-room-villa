@php($room = $booking->rooms->first())
<x-mail::message>
# {{ __('Booking Anda terkonfirmasi') }}

{{ __('Halo :name, pembayaran Anda sudah diverifikasi. Sampai jumpa di :villa!', ['name' => $booking->guest_name, 'villa' => config('villa.name')]) }}

- **{{ __('Kode booking') }}:** {{ $booking->code }}
- **{{ __('Kamar') }}:** {{ $room?->roomType->name }} - {{ $room?->name }}
- **Check-in:** {{ $booking->check_in->format('d/m/Y') }}
- **Check-out:** {{ $booking->check_out->format('d/m/Y') }}
- **Total:** Rp {{ number_format($booking->total_price, 0, ',', '.') }}

<x-mail::button :url="route('booking.show', $booking->code)">
{{ __('Lihat Status Booking') }}
</x-mail::button>

{{ config('villa.name') }}
</x-mail::message>
