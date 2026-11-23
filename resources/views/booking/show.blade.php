@extends('layouts.public')

@section('title', __('Status Booking') . ' - ' . config('villa.name'))

@section('content')
@php($room = $booking->rooms->first())

<div class="mx-auto max-w-3xl px-4 py-8">
    <h1 class="text-2xl font-bold">{{ __('Status Booking') }}</h1>

    <div class="mt-4 rounded border p-4">
        <p class="text-sm text-gray-500">{{ __('Kode booking') }}</p>
        <p class="text-2xl font-bold tracking-wide">{{ $booking->code }}</p>
        <p class="mt-1 text-sm text-gray-500">{{ __('Simpan kode ini untuk mengecek status booking.') }}</p>
    </div>

    <dl class="mt-4 space-y-2 text-sm">
        <div class="flex justify-between border-b py-2">
            <dt>{{ __('Status') }}</dt>
            <dd class="font-semibold">{{ $booking->status_label }}</dd>
        </div>
        <div class="flex justify-between border-b py-2">
            <dt>{{ __('Nama') }}</dt>
            <dd>{{ $booking->guest_name }}</dd>
        </div>
        <div class="flex justify-between border-b py-2">
            <dt>{{ __('Kamar') }}</dt>
            <dd>{{ $room?->roomType->name }} - {{ $room?->name }}</dd>
        </div>
        <div class="flex justify-between border-b py-2">
            <dt>Check-in / Check-out</dt>
            <dd>{{ $booking->check_in->format('d/m/Y') }} &rarr; {{ $booking->check_out->format('d/m/Y') }}</dd>
        </div>
        <div class="flex justify-between border-b py-2">
            <dt>{{ __('Jumlah tamu') }}</dt>
            <dd>{{ $booking->guests_count }}</dd>
        </div>
        <div class="flex justify-between border-b py-2">
            <dt>Total</dt>
            <dd class="font-semibold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
        </div>
        @if ($booking->status === \App\Models\Booking::PENDING && $booking->expires_at)
            <div class="flex justify-between border-b py-2">
                <dt>{{ __('Bayar sebelum') }}</dt>
                <dd>{{ $booking->expires_at->format('d/m/Y H:i') }} WITA</dd>
            </div>
        @endif
    </dl>

    @php($payment = $booking->payments->sortByDesc('id')->first())

    @if ($payment)
        <div class="mt-4 rounded border p-4 text-sm">
            <p>{{ __('Status pembayaran') }}: <span class="font-semibold">{{ $payment->status_label }}</span></p>
            @if ($payment->status === \App\Models\Payment::REJECTED && $payment->note)
                <p class="mt-1 text-red-700">{{ __('Alasan') }}: {{ $payment->note }}</p>
            @endif
        </div>
    @endif

    @if ($booking->isPayable() && $booking->payments->whereIn('status', ['pending', 'verified'])->isEmpty())
        <a href="{{ route('booking.pay', $booking->code) }}"
            class="mt-6 inline-block rounded bg-brand-600 px-5 py-2 text-white hover:bg-brand-700">
            {{ __('Bayar Sekarang') }}
        </a>
    @endif
</div>
@endsection