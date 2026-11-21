@extends('layouts.admin')

@section('title', 'Kalender')

@section('content')
@php
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $hari = ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'];
@endphp

<div class="mb-4 flex flex-wrap items-center justify-between gap-2">
    <h1 class="text-2xl font-bold">Kalender - {{ $bulan[$start->month] }} {{ $start->year }}</h1>
    <div class="flex gap-2 text-sm">
        <a href="{{ route('admin.calendar', ['month' => $prev]) }}"
            class="rounded border bg-white px-3 py-1 hover:bg-gray-50">&larr;</a>
        <a href="{{ route('admin.calendar') }}" class="rounded border bg-white px-3 py-1 hover:bg-gray-50">Bulan ini</a>
        <a href="{{ route('admin.calendar', ['month' => $next]) }}"
            class="rounded border bg-white px-3 py-1 hover:bg-gray-50">&rarr;</a>
    </div>
</div>

<div class="mb-3 flex flex-wrap gap-4 text-xs">
    <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 bg-green-500"></span> Terisi</span>
    <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 bg-yellow-300"></span> Menunggu
        pembayaran</span>
    <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 bg-gray-400"></span> Diblokir</span>
</div>

<div class="overflow-x-auto rounded bg-white shadow">
    <table class="min-w-full border-collapse text-xs">
        <thead>
            <tr>
                <th class="sticky left-0 bg-gray-50 p-2 text-left">Kamar</th>
                @foreach ($days as $day)
                    <th class="w-8 p-1 text-center {{ $day->isToday() ? 'bg-blue-100' : 'bg-gray-50' }}">
                        {{ $day->day }}<br>
                        <span class="font-normal text-gray-400">{{ $hari[$day->dayOfWeek] }}</span>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
            <tr class="border-t">
                <td class="sticky left-0 whitespace-nowrap bg-white p-2">
                    {{ $room->roomType->name }} - {{ $room->name }}
                </td>
                @foreach ($days as $day)
                @php($cell = $cells[$room->id][$day->toDateString()] ?? null)
                <td class="h-8 border p-0">
                    @if ($cell && $cell['booking'])
                        <a href="{{ route('admin.reservations.show', $cell['booking']) }}"
                            title="{{ $cell['booking']->code }} - {{ $cell['booking']->guest_name }}"
                            class="block h-8 {{ $cell['state'] === 'pending' ? 'bg-yellow-300' : 'bg-green-500' }}"></a>
                    @elseif ($cell)
                        <div class="h-8 bg-gray-400" title="{{ $cell['reason'] ?: 'Diblokir' }}"></div>
                    @endif
                </td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td class="p-3 text-gray-500">Belum ada kamar aktif.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection