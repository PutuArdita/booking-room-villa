@extends('layouts.admin')

@section('title', 'Dasbor')

@section('content')
<h1 class="text-2xl font-bold">Dasbor</h1>
<p class="mb-4 text-sm text-gray-500">Selamat datang, {{ auth()->user()->name }}.</p>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
    @foreach ([
            ['Perlu verifikasi', $stats['perlu_verifikasi'], route('admin.reservations.index', ['status' => 'perlu_verifikasi'])],
            ['Menunggu pembayaran', $stats['menunggu_bayar'], route('admin.reservations.index', ['status' => 'pending_payment'])],
            ['Check-in hari ini', $stats['checkin_hari_ini'], route('admin.reservations.index', ['status' => 'confirmed'])],
            ['Check-out hari ini', $stats['checkout_hari_ini'], route('admin.reservations.index', ['status' => 'checked_in'])],
            ['Sedang menginap', $stats['menginap'], route('admin.reservations.index', ['status' => 'checked_in'])],
        ] as [$label, $count, $url])
        <a href="{{ $url }}" class="rounded bg-white p-4 shadow hover:shadow-md">
            <p class="text-sm text-gray-500">{{ $label }}</p>
            <p class="text-3xl font-bold">{{ $count }}</p>
        </a>
    @endforeach
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    @foreach (['Check-in hari ini' => $checkIns, 'Check-out hari ini' => $checkOuts] as $title => $items)
    <div class="rounded bg-white p-4 shadow">
        <h2 class="mb-2 font-bold">{{ $title }}</h2>
        @forelse ($items as $booking)
        @php($room = $booking->rooms->first())
        <a href="{{ route('admin.reservations.show', $booking) }}"
            class="flex justify-between border-b py-2 text-sm hover:bg-gray-50">
            <span>{{ $booking->guest_name }} <span class="text-gray-500">({{ $booking->code }})</span></span>
            <span>{{ $room?->roomType->name }} - {{ $room?->name }}</span>
        </a>
        @empty
        <p class="text-sm text-gray-500">Tidak ada.</p>
        @endforelse
    </div>
    @endforeach
</div>
@endsection