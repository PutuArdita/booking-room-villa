@extends('layouts.public')

@section('title', __('Cek Ketersediaan') . ' - ' . config('villa.name'))

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8">
        <h1 class="mb-4 text-2xl font-bold">{{ __('Cek Ketersediaan') }}</h1>

        @if ($errors->any())
            <ul class="mb-4 list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="GET" action="{{ route('availability.index') }}" class="grid gap-4 rounded border p-4 sm:grid-cols-4">
            <div>
                <label class="block text-sm">Check-in</label>
                <input type="date" name="check_in" value="{{ request('check_in') }}" min="{{ today()->toDateString() }}"
                    required class="w-full rounded border p-2">
            </div>
            <div>
                <label class="block text-sm">Check-out</label>
                <input type="date" name="check_out" value="{{ request('check_out') }}"
                    min="{{ today()->addDay()->toDateString() }}" required class="w-full rounded border p-2">
            </div>
            <div>
                <label class="block text-sm">{{ __('Jumlah tamu') }}</label>
                <input type="number" name="guests" min="1" max="50" value="{{ request('guests', 2) }}"
                    required class="w-full rounded border p-2">
            </div>
            <div class="flex items-end">
                <button class="w-full rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    {{ __('Cari') }}
                </button>
            </div>
        </form>

        @if ($results !== null)
            <p class="mt-6 text-sm text-gray-600">
                {{ request('check_in') }} &rarr; {{ request('check_out') }} ({{ $nights }} {{ __('malam') }})
            </p>

            <div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($results as $type)
                    <div class="overflow-hidden rounded border">
                        @if ($type->images->first())
                            <img src="{{ $type->images->first()->url }}" alt="{{ $type->name }}"
                                class="h-48 w-full object-cover">
                        @else
                            <div class="flex h-48 items-center justify-center bg-gray-100 text-sm text-gray-400">
                                {{ __('Belum ada foto') }}
                            </div>
                        @endif

                        <div class="space-y-2 p-4">
                            <h3 class="font-bold">{{ $type->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $type->capacity }} {{ __('tamu') }}</p>
                            <p class="text-sm">
                                Rp {{ number_format($type->base_price, 0, ',', '.') }} / {{ __('malam') }}
                            </p>
                            <p class="font-semibold">
                                Total: Rp {{ number_format($type->base_price * $nights, 0, ',', '.') }}
                            </p>

                            <div>
                                <p class="text-sm text-gray-600">
                                    {{ $type->availableRooms->count() }} {{ __('kamar tersedia') }}
                                </p>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @foreach ($type->availableRooms as $room)
                                        <a href="{{ route('booking.create', [
                                            'room_id' => $room->id,
                                            'check_in' => request('check_in'),
                                            'check_out' => request('check_out'),
                                            'guests' => request('guests'),
                                        ]) }}"
                                            class="rounded bg-green-100 px-3 py-1 text-sm text-green-800 hover:bg-green-200">
                                            {{ $room->name }} &rarr; {{ __('Pesan') }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <a href="{{ route('rooms.show', $type->slug) }}"
                                class="inline-block text-sm text-blue-600 hover:underline">
                                {{ __('Lihat detail') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">{{ __('Tidak ada kamar tersedia pada tanggal tersebut.') }}</p>
                @endforelse
            </div>
        @endif
    </div>
@endsection
