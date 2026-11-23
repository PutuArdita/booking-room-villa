@extends('layouts.public')

@section('title', __('Form Booking') . ' - ' . config('villa.name'))

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8">
        <h1 class="mb-4 text-2xl font-bold">{{ __('Form Booking') }}</h1>

        @if ($errors->any())
            <ul class="mb-4 list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="mb-6 space-y-1 rounded border bg-gray-50 p-4 text-sm">
            <p class="font-semibold">{{ $room->roomType->name }} - {{ $room->name }}</p>
            <p>{{ $data['check_in'] }} &rarr; {{ $data['check_out'] }} ({{ $nights }} {{ __('malam') }})</p>
            <p>{{ $data['guests'] }} {{ __('tamu') }}</p>
            <p class="text-lg font-semibold">Total: Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>

        <form method="POST" action="{{ route('booking.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->id }}">
            <input type="hidden" name="check_in" value="{{ $data['check_in'] }}">
            <input type="hidden" name="check_out" value="{{ $data['check_out'] }}">
            <input type="hidden" name="guests" value="{{ $data['guests'] }}">

            <div>
                <label class="block text-sm">{{ __('Nama lengkap') }}</label>
                <input type="text" name="guest_name" value="{{ old('guest_name') }}" required
                    class="input">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm">Email</label>
                    <input type="email" name="guest_email" value="{{ old('guest_email') }}" required
                        class="input">
                </div>
                <div>
                    <label class="block text-sm">{{ __('Nomor HP') }}</label>
                    <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" required
                        class="input">
                </div>
            </div>

            <div>
                <label class="block text-sm">{{ __('Catatan (opsional)') }}</label>
                <textarea name="notes" rows="3" class="input">{{ old('notes') }}</textarea>
            </div>

            <p class="rounded bg-yellow-50 p-3 text-sm text-yellow-800">
                {{ __('Pembayaran langsung lunas. Booking dibatalkan otomatis jika belum dibayar dalam :hours jam. Booking tidak dapat dibatalkan setelah dibuat.', ['hours' => config('villa.payment_hours')]) }}
            </p>

            <button class="rounded bg-brand-600 px-5 py-2 text-white hover:bg-brand-700">
                {{ __('Buat Booking') }}
            </button>
        </form>
    </div>
@endsection