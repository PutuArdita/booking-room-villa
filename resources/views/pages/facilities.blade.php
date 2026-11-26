@extends('layouts.public')

@section('title', __('Fasilitas') . ' - ' . config('villa.name'))

@section('content')
@include('layouts._page-header', [
    'title' => __('Fasilitas Kami'),
    'subtitle' => __('Semua yang Anda butuhkan untuk menginap dengan nyaman.'),
])

@php($locale = app()->getLocale())

<div class="mx-auto max-w-7xl px-4 py-12 lg:px-6">
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach (config('villa.facilities') as $facility)
        @php($hasImage = !empty($facility['image']) && file_exists(public_path($facility['image'])))

        <article data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 100 }}ms"
            class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-sand-100">
            @if ($hasImage)
                <img src="{{ asset($facility['image']) }}" alt="{{ $facility['name'][$locale] }}" loading="lazy"
                    class="aspect-[4/3] w-full object-cover">
            @endif
            <div class="p-5">
                <span class="inline-flex rounded-lg bg-brand-100 p-2 text-brand-700">
                    <x-dynamic-component :component="'heroicon-o-' . $facility['icon']" class="h-6 w-6" />
                </span>
                <h2 class="mt-3 font-display text-xl font-semibold text-gray-900">{{ $facility['name'][$locale] }}</h2>
                <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $facility['description'][$locale] }}</p>
            </div>
        </article>
        @endforeach
    </div>
</div>
@endsection