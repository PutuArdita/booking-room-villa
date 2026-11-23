@extends('layouts.admin')

@section('title', 'Reservasi ' . $booking->code)

@section('content')
@php($room = $booking->rooms->first())

<a href="{{ route('admin.reservations.index') }}" class="text-sm text-brand-600 hover:underline">&larr; Kembali</a>

<div class="mt-2 mb-4 flex items-center gap-3">
    <h1 class="text-2xl font-bold">{{ $booking->code }}</h1>
    @include('admin.reservations._badge')
</div>

@if ($errors->any())
    <ul class="mb-4 list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-6">
        <div class="rounded bg-white p-4 shadow">
            <h2 class="mb-2 font-bold">Data tamu</h2>
            <dl class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <dt>Nama</dt>
                    <dd>{{ $booking->guest_name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt>Email</dt>
                    <dd>{{ $booking->guest_email }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt>HP</dt>
                    <dd>{{ $booking->guest_phone }}</dd>
                </div>
                @if ($booking->notes)
                    <div>
                        <dt>Catatan</dt>
                        <dd class="text-gray-600">{{ $booking->notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="rounded bg-white p-4 shadow">
            <h2 class="mb-2 font-bold">Booking</h2>
            <dl class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <dt>Kamar</dt>
                    <dd>{{ $room?->roomType->name }} - {{ $room?->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt>Check-in</dt>
                    <dd>{{ $booking->check_in->format('d/m/Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt>Check-out</dt>
                    <dd>{{ $booking->check_out->format('d/m/Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt>Malam</dt>
                    <dd>{{ (int) $booking->check_in->diffInDays($booking->check_out) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt>Jumlah tamu</dt>
                    <dd>{{ $booking->guests_count }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt>Total</dt>
                    <dd class="font-semibold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
                </div>
                @if ($booking->status === \App\Models\Booking::PENDING && $booking->expires_at)
                    <div class="flex justify-between">
                        <dt>Batas bayar</dt>
                        <dd>{{ $booking->expires_at->format('d/m/Y H:i') }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        @if ($transitions)
            <div class="rounded bg-white p-4 shadow">
                <h2 class="mb-2 font-bold">Ubah status</h2>
                <div class="flex flex-wrap items-start gap-3">
                    @foreach ($transitions as $to)
                        @if ($to === \App\Models\Booking::CANCELLED)
                            <details>
                                <summary class="cursor-pointer rounded border border-red-300 px-3 py-1 text-sm text-red-600">
                                    Batalkan booking</summary>
                                <form method="POST" action="{{ route('admin.reservations.status', $booking) }}"
                                    class="mt-2 space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $to }}">
                                    <textarea name="note" rows="2" required placeholder="Alasan pembatalan"
                                        class="w-64 rounded border p-2 text-sm"></textarea>
                                    <button class="block rounded bg-red-600 px-3 py-1 text-sm text-white hover:bg-red-700"
                                        onclick="return confirm('Batalkan booking ini?')">Batalkan</button>
                                </form>
                            </details>
                        @else
                            <form method="POST" action="{{ route('admin.reservations.status', $booking) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $to }}">
                                <button class="rounded bg-brand-600 px-3 py-1 text-sm text-white hover:bg-brand-700">
                                    {{ $to === \App\Models\Booking::CHECKED_IN ? 'Check-in' : 'Check-out' }}
                                </button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="rounded bg-white p-4 shadow">
            <h2 class="mb-2 font-bold">Pembayaran</h2>

            @forelse ($booking->payments->sortByDesc('id') as $payment)
                <div class="mb-3 rounded border p-3 text-sm">
                    <div class="flex justify-between">
                        <span>{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                        <span class="font-semibold">{{ $payment->status_label }}</span>
                    </div>
                    <p>Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    <a href="{{ route('admin.payments.proof', $payment) }}" target="_blank"
                        class="text-brand-600 hover:underline">Lihat bukti transfer</a>

                    @if ($payment->note)
                        <p class="mt-1 text-gray-600">Catatan: {{ $payment->note }}</p>
                    @endif
                    @if ($payment->verifier)
                        <p class="text-gray-500">Oleh {{ $payment->verifier->name }},
                            {{ $payment->verified_at?->format('d/m/Y H:i') }}
                        </p>
                    @endif

                    @if ($payment->status === \App\Models\Payment::PENDING)
                        <div class="mt-3 flex flex-wrap items-start gap-3">
                            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}"
                                onsubmit="return confirm('Verifikasi pembayaran ini?')">
                                @csrf
                                <button class="rounded bg-green-600 px-3 py-1 text-white hover:bg-green-700">Verifikasi</button>
                            </form>
                            <details>
                                <summary class="cursor-pointer text-red-600">Tolak</summary>
                                <form method="POST" action="{{ route('admin.payments.reject', $payment) }}"
                                    class="mt-2 space-y-2">
                                    @csrf
                                    <textarea name="note" rows="2" required placeholder="Alasan penolakan"
                                        class="w-64 rounded border p-2"></textarea>
                                    <button class="block rounded bg-red-600 px-3 py-1 text-white hover:bg-red-700">Kirim
                                        penolakan</button>
                                </form>
                            </details>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada bukti pembayaran.</p>
            @endforelse
        </div>

        <div class="rounded bg-white p-4 shadow">
            <h2 class="mb-2 font-bold">Riwayat status</h2>
            <ul class="space-y-2 text-sm">
                @foreach ($booking->statusLogs as $log)
                    <li class="border-b pb-2">
                        <span class="text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                        &middot; <span class="font-semibold">{{ \App\Models\Booking::statusText($log->to_status) }}</span>
                        &middot; {{ $log->user?->name ?? 'Tamu/sistem' }}
                        @if ($log->note)
                            <br><span class="text-gray-600">{{ $log->note }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection