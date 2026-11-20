@extends('layouts.public')

@section('title', __('Pembayaran') . ' - ' . config('villa.name'))

@section('content')
    @php
        $payment = $booking->payments->sortByDesc('id')->first();
        $hasActive = $booking->payments->whereIn('status', ['pending', 'verified'])->isNotEmpty();
        $canUpload = $booking->isPayable() && !$hasActive;
    @endphp

    <div class="mx-auto max-w-3xl px-4 py-8">
        <a href="{{ route('booking.show', $booking->code) }}" class="text-sm text-blue-600 hover:underline">
            &larr; {{ __('Kembali ke status booking') }}
        </a>

        <h1 class="mt-2 text-2xl font-bold">{{ __('Pembayaran') }}</h1>
        <p class="text-sm text-gray-500">
            {{ __('Kode booking') }}: <span class="font-semibold">{{ $booking->code }}</span>
        </p>

        @if ($errors->any())
            <ul class="mt-4 list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="mt-4 space-y-1 rounded border bg-gray-50 p-4 text-sm">
            <p>{{ __('Total yang harus dibayar') }}</p>
            <p class="text-2xl font-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
            @if ($booking->isPayable())
                <p>{{ __('Bayar sebelum') }}: {{ $booking->expires_at->format('d/m/Y H:i') }} WITA</p>
            @endif
        </div>

        <div class="mt-4 space-y-1 rounded border p-4 text-sm">
            <p class="font-semibold">{{ __('Transfer ke rekening') }}</p>
            <p>{{ __('Bank') }}: {{ config('villa.bank.name') }}</p>
            <p>{{ __('Nomor rekening') }}: <span class="font-mono">{{ config('villa.bank.account_number') }}</span></p>
            <p>{{ __('Atas nama') }}: {{ config('villa.bank.account_holder') }}</p>
        </div>

        @if ($payment)
            <div class="mt-4 rounded border p-4 text-sm">
                <p>{{ __('Status pembayaran') }}: <span class="font-semibold">{{ $payment->status_label }}</span></p>
                @if ($payment->status === \App\Models\Payment::REJECTED && $payment->note)
                    <p class="mt-1 text-red-700">{{ __('Alasan') }}: {{ $payment->note }}</p>
                @endif
            </div>
        @endif

        @if ($canUpload)
            <form method="POST" action="{{ route('booking.pay.store', $booking->code) }}" enctype="multipart/form-data"
                class="mt-4 space-y-3 rounded border p-4">
                @csrf
                <label class="block text-sm font-semibold">{{ __('Unggah bukti transfer') }}</label>
                <input type="file" name="proof" required accept="image/jpeg,image/png,image/webp,application/pdf"
                    class="block w-full text-sm">
                <p class="text-xs text-gray-500">{{ __('Maksimal 3 MB (jpg, png, webp, atau pdf).') }}</p>
                <button class="rounded bg-blue-600 px-5 py-2 text-white hover:bg-blue-700">
                    {{ __('Unggah') }}
                </button>
            </form>
        @elseif ($hasActive)
            <p class="mt-4 rounded bg-blue-50 p-3 text-sm text-blue-800">
                {{ __('Bukti transfer sudah diunggah dan sedang diproses oleh admin.') }}
            </p>
        @else
            <p class="mt-4 rounded bg-red-50 p-3 text-sm text-red-800">
                {{ __('Booking ini tidak dapat dibayar lagi.') }}
            </p>
        @endif
    </div>
@endsection