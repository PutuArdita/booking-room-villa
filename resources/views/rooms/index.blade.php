@extends('layouts.public')

@section('title', __('Kamar') . ' - ' . config('villa.name'))

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8">
        <h1 class="mb-4 text-2xl font-bold">{{ __('Kamar Kami') }}</h1>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($roomTypes as $type)
                @include('rooms._card')
            @empty
                <p class="text-gray-500">{{ __('Belum ada kamar tersedia.') }}</p>
            @endforelse
        </div>
    </div>
@endsection
