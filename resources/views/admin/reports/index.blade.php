@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
    @php
        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $rp = fn($n) => 'Rp ' . number_format($n, 0, ',', '.');
        $pct = fn($n) => number_format($n, 1, ',', '.') . '%';
        $summary = $report['summary'];
    @endphp

    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <h1 class="text-2xl font-bold">Laporan - {{ $bulan[$month->month] }} {{ $month->year }}</h1>
        <div class="flex flex-wrap gap-2 text-sm">
            <a href="{{ route('admin.reports.index', ['month' => $prev]) }}"
                class="rounded border bg-white px-3 py-1 hover:bg-gray-50">&larr;</a>
            <a href="{{ route('admin.reports.index') }}" class="rounded border bg-white px-3 py-1 hover:bg-gray-50">Bulan
                ini</a>
            <a href="{{ route('admin.reports.index', ['month' => $next]) }}"
                class="rounded border bg-white px-3 py-1 hover:bg-gray-50">&rarr;</a>
            <a href="{{ route('admin.reports.export', ['month' => $month->format('Y-m')]) }}"
                class="rounded bg-green-600 px-3 py-1 text-white hover:bg-green-700">Ekspor CSV</a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ([
                ['Pendapatan', $rp($summary['revenue'])],
                ['Booking', $summary['bookings']],
                ['Malam terjual', $summary['nights']],
                ['Okupansi', $pct($summary['occupancy'])],
                ['Rata-rata harga/malam', $rp($summary['adr'])],
            ] as [$label, $value])
            <div class="rounded bg-white p-4 shadow">
                <p class="text-sm text-gray-500">{{ $label }}</p>
                <p class="text-2xl font-bold">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 overflow-x-auto rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Tipe kamar</th>
                    <th class="p-3">Booking</th>
                    <th class="p-3">Malam terjual</th>
                    <th class="p-3">Okupansi</th>
                    <th class="p-3">Rata-rata/malam</th>
                    <th class="p-3">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($report['rows'] as $row)
                    <tr class="border-t">
                        <td class="p-3">{{ $row['name'] }}</td>
                        <td class="p-3">{{ $row['booking_count'] }}</td>
                        <td class="p-3">{{ $row['nights'] }}</td>
                        <td class="p-3">{{ $pct($row['occupancy']) }}</td>
                        <td class="p-3">{{ $rp($row['adr']) }}</td>
                        <td class="p-3">{{ $rp($row['revenue']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-3 text-center text-gray-500">Belum ada tipe kamar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($report['cancelled']['count'] > 0)
        <p class="mt-4 rounded bg-yellow-50 p-3 text-sm text-yellow-800">
            Catatan: {{ $report['cancelled']['count'] }} booking dibatalkan setelah pembayaran terverifikasi
            (nilai {{ $rp($report['cancelled']['amount']) }}). Nilai ini tidak termasuk dalam angka di atas.
        </p>
    @endif

    <p class="mt-4 text-xs text-gray-500">
        Pendapatan dihitung per malam menginap pada bulan ini, dari booking terkonfirmasi, check-in, dan check-out.
        Booking menunggu pembayaran, kedaluwarsa, dan dibatalkan tidak dihitung.
    </p>
@endsection