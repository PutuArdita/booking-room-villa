@extends('layouts.public')
{{-- @include('layouts._page-header') --}}

@section('title', $roomType->name . ' - ' . config('villa.name'))

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8">
        <a href="{{ route('rooms.index') }}" class="text-sm text-brand-600 hover:underline">
            &larr; {{ __('Kembali ke daftar kamar') }}
        </a>
        <h1 class="mt-2 text-3xl font-bold">{{ $roomType->name }}</h1>

        <div class="mt-4 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($roomType->images as $image)
                <img src="{{ $image->url }}" alt="{{ $roomType->name }}" class="h-56 w-full rounded object-cover">
            @empty
                <div class="flex h-56 items-center justify-center rounded bg-gray-100 text-sm text-gray-400">
                    {{ __('Belum ada foto') }}
                </div>
            @endforelse
        </div>

        <div class="mt-6 space-y-2">
            <p>{{ __('Kapasitas') }}: {{ $roomType->capacity }} {{ __('tamu') }}</p>
            <p class="text-xl font-semibold">
                Rp {{ number_format($roomType->base_price, 0, ',', '.') }}
                <span class="text-sm font-normal text-gray-500">/ {{ __('malam') }}</span>
            </p>
            @if ($roomType->localized_description)
                <div class="pt-2">{!! nl2br(e($roomType->localized_description)) !!}</div>
            @endif
        </div>
    </div>
@endsection
