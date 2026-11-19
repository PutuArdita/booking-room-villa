@extends('layouts.public')

@section('content')
    <section class="bg-gray-100">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <h1 class="text-3xl font-bold">{{ config('villa.name') }}</h1>
            <p class="mt-2 text-lg text-gray-600">{{ config('villa.tagline.' . app()->getLocale()) }}</p>
            <p class="mt-4 max-w-2xl">{{ config('villa.description.' . app()->getLocale()) }}</p>
            <a href="{{ route('rooms.index') }}"
               class="mt-6 inline-block rounded bg-blue-600 px-5 py-2 text-white hover:bg-blue-700">
                {{ __('Lihat Kamar') }}
            </a>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-10">
        <h2 class="mb-4 text-2xl font-bold">{{ __('Kamar Kami') }}</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($roomTypes as $type)
                @include('rooms._card')
            @empty
                <p class="text-gray-500">{{ __('Belum ada kamar tersedia.') }}</p>
            @endforelse
        </div>
    </section>
@endsection
