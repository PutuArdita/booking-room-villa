@extends('layouts.public')

@section('title', __('Kontak') . ' - ' . config('villa.name'))

@section('content')
@include('layouts._page-header', [
    'title' => __('Hubungi Kami'),
    'subtitle' => __('Kami siap membantu pertanyaan dan reservasi Anda.'),
])

@php($locale = app()->getLocale())

<div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 lg:grid-cols-2 lg:px-6">
    <div data-reveal="left">
        <ul class="space-y-5 text-gray-700">
            @if (config('villa.address'))
                <li class="flex items-start gap-3">
                    <x-heroicon-o-map-pin class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" />
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ __('Alamat') }}</p>
                        <p>{{ config('villa.address') }}</p>
                    </div>
                </li>
            @endif
            @if (config('villa.phone'))
                <li class="flex items-start gap-3">
                    <x-heroicon-o-phone class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" />
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ __('Telepon') }}</p>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', config('villa.phone')) }}"
                            class="hover:underline">{{ config('villa.phone') }}</a>
                    </div>
                </li>
            @endif
            @if (config('villa.email'))
                <li class="flex items-start gap-3">
                    <x-heroicon-o-envelope class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" />
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Email</p>
                        <a href="mailto:{{ config('villa.email') }}" class="hover:underline">{{ config('villa.email') }}</a>
                    </div>
                </li>
            @endif
            @if (config('villa.hours'))
                <li class="flex items-start gap-3">
                    <x-heroicon-o-clock class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" />
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ __('Jam layanan') }}</p>
                        <p>{{ config('villa.hours.' . $locale) }}</p>
                    </div>
                </li>
            @endif
        </ul>

        <div class="mt-8 flex flex-wrap gap-3">
            @if (config('villa.whatsapp'))
                <x-button :href="'https://wa.me/' . config('villa.whatsapp') . '?text=' . rawurlencode(__('Halo, saya ingin bertanya tentang reservasi.'))" target="_blank" rel="noopener">
                    <x-heroicon-o-chat-bubble-left-right class="h-5 w-5" /> {{ __('Chat WhatsApp') }}
                </x-button>
            @endif
            @if (config('villa.maps_url'))
                <x-button :href="config('villa.maps_url')" variant="secondary" target="_blank" rel="noopener">
                    <x-heroicon-o-map class="h-5 w-5" /> {{ __('Buka di Google Maps') }}
                </x-button>
            @endif
        </div>
    </div>

    <div data-reveal="right">
        @if (config('villa.maps_embed_url'))
            <iframe src="{{ config('villa.maps_embed_url') }}" title="{{ config('villa.name') }}"
                class="h-80 w-full rounded-3xl border-0 shadow-lg lg:h-full lg:min-h-96" loading="lazy" allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        @else
            <div
                class="flex h-80 w-full items-center justify-center rounded-3xl bg-brand-100 text-sm text-brand-700 lg:h-full">
                {{ __('Peta belum diatur.') }}
            </div>
        @endif
    </div>
</div>
@endsection