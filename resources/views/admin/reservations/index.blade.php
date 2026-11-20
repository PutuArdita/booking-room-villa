@extends('layouts.admin')

@section('title', 'Reservasi')

@section('content')
<h1 class="mb-4 text-2xl font-bold">Reservasi</h1>

<form method="GET" class="mb-4 flex flex-wrap items-center gap-2 text-sm">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, email, HP"
        class="w-64 rounded border p-2">
    <select name="status" class="rounded border p-2" onchange="this.form.submit()">
        <option value="">Semua status</option>
        <option value="perlu_verifikasi" @selected(request('status') === 'perlu_verifikasi')>Perlu verifikasi</option>
        @foreach ([
                'pending_payment' => 'Menunggu pembayaran',
                'confirmed' => 'Terkonfirmasi',
                'checked_in' => 'Check-in',
                'checked_out' => 'Check-out',
                'cancelled' => 'Dibatalkan',
                'expired' => 'Kedaluwarsa',
            ] as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Cari</button>
</form>

<div class="overflow-x-auto rounded bg-white shadow">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3">Kode</th>
                <th class="p-3">Tamu</th>
                <th class="p-3">Kamar</th>
                <th class="p-3">Tanggal</th>
                <th class="p-3">Total</th>
                <th class="p-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
            @php($room = $booking->rooms->first())
            <tr class="border-t">
                <td class="p-3">
                    <a href="{{ route('admin.reservations.show', $booking) }}"
                        class="font-semibold text-blue-600 hover:underline">{{ $booking->code }}</a>
                </td>
                <td class="p-3">{{ $booking->guest_name }}<br>
                    <span class="text-gray-500">{{ $booking->guest_phone }}</span>
                </td>
                <td class="p-3">{{ $room?->roomType->name }} - {{ $room?->name }}</td>
                <td class="p-3">{{ $booking->check_in->format('d/m/Y') }} &rarr;
                    {{ $booking->check_out->format('d/m/Y') }}</td>
                <td class="p-3">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td class="p-3">
                    @include('admin.reservations._badge')
                    @if ($booking->payments->where('status', 'pending')->isNotEmpty())
                        <span class="ml-1 rounded bg-orange-100 px-2 py-1 text-xs text-orange-800">Perlu verifikasi</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-3 text-center text-gray-500">Belum ada reservasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $bookings->links() }}</div>
@endsection