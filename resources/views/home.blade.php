@extends('layouts.public')

@section('transparent-nav', '1')

@section('content')
    @php
        $locale = app()->getLocale();
        $about = config('villa.about');
        $heroImages = collect(config('villa.hero_images', []))
            ->filter(fn ($path) => file_exists(public_path($path)))
            ->values();
    @endphp

    {{-- HERO --}}
    <section class="relative isolate flex min-h-[100svh] items-end overflow-hidden bg-brand-900">
        @foreach ($heroImages as $i => $path)
            <img src="{{ asset($path) }}" alt="" data-hero-slide
                 class="absolute inset-0 -z-10 h-full w-full object-cover transition-opacity duration-1000 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}"
                 @if ($i > 0) loading="lazy" @endif>
        @endforeach
        <div class="absolute inset-0 -z-10 bg-black/40"></div>

        <div class="mx-auto w-full max-w-7xl px-4 pb-10 pt-32 lg:px-6 lg:pb-16">
            <p data-reveal class="text-sm uppercase tracking-[0.3em] text-brand-100">
                {{ __('Selamat datang di :villa', ['villa' => config('villa.name')]) }}
            </p>
            <h1 data-reveal style="--reveal-delay: 100ms"
                class="mt-3 max-w-3xl font-display text-4xl font-semibold leading-tight text-white sm:text-6xl">
                {{ config('villa.tagline.' . $locale) }}
            </h1>

            <form method="GET" action="{{ route('availability.index') }}" data-reveal style="--reveal-delay: 250ms"
                  class="mt-8 grid gap-3 rounded-2xl bg-white p-4 shadow-xl sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Check-in</label>
                    <input type="date" name="check_in" min="{{ today()->toDateString() }}" required class="input">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Check-out</label>
                    <input type="date" name="check_out" min="{{ today()->addDay()->toDateString() }}" required class="input">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">{{ __('Jumlah tamu') }}</label>
                    <input type="number" name="guests" min="1" max="50" value="2" required class="input">
                </div>
                <div class="flex items-end">
                    <x-button class="w-full">
                        <x-heroicon-o-magnifying-glass class="h-5 w-5" /> {{ __('Cari') }}
                    </x-button>
                </div>
            </form>
        </div>
    </section>

    {{-- TENTANG --}}
    <section id="tentang" class="scroll-mt-16 py-16 sm:py-24">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 lg:grid-cols-2 lg:gap-16 lg:px-6">
            <div data-reveal="left">
                <p class="text-sm uppercase tracking-[0.3em] text-brand-600">{{ __('Tentang') }}</p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-gray-900 sm:text-4xl">
                    {{ $about['title'][$locale] }}
                </h2>

                <div class="mt-6 space-y-4 leading-relaxed text-gray-600">
                    @foreach ($about['paragraphs'][$locale] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>

                <ul class="mt-8 grid gap-4 sm:grid-cols-3">
                    @foreach ($about['highlights'] as $highlight)
                        <li class="flex items-start gap-3">
                            <span class="rounded-lg bg-brand-100 p-2 text-brand-700">
                                <x-dynamic-component :component="'heroicon-o-' . $highlight['icon']" class="h-5 w-5" />
                            </span>
                            <span class="text-sm font-medium text-gray-700">{{ $highlight[$locale] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div data-reveal="right">
                @if (file_exists(public_path('images/about.jpg')))
                    <img src="{{ asset('images/about.jpg') }}" alt="{{ config('villa.name') }}" loading="lazy"
                         class="aspect-[4/5] w-full rounded-3xl object-cover shadow-xl">
                @else
                    <div class="aspect-[4/5] w-full rounded-3xl bg-brand-100"></div>
                @endif
            </div>
        </div>
    </section>

    {{-- KAMAR --}}
    <section id="kamar" class="scroll-mt-16 bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 lg:px-6">
            <div data-reveal class="mx-auto max-w-2xl text-center">
                <p class="text-sm uppercase tracking-[0.3em] text-brand-600">{{ __('Kamar') }}</p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-gray-900 sm:text-4xl">{{ __('Kamar Kami') }}</h2>
                <p class="mt-3 text-gray-600">{{ __('Pilihan kamar nyaman untuk setiap kebutuhan menginap Anda.') }}</p>
            </div>

            <div class="mt-12 space-y-16">
                @forelse ($roomTypes as $type)
                    <article class="grid items-center gap-8 lg:grid-cols-2 lg:gap-14">
                        <div data-reveal="{{ $loop->even ? 'right' : 'left' }}" @class(['lg:order-2' => $loop->even])>
                            @if ($type->images->first())
                                <img src="{{ $type->images->first()->url }}" alt="{{ $type->name }}" loading="lazy"
                                     class="aspect-[4/3] w-full rounded-3xl object-cover shadow-lg">
                            @else
                                <div class="flex aspect-[4/3] w-full items-center justify-center rounded-3xl bg-sand-100 text-sm text-gray-400">
                                    {{ __('Belum ada foto') }}
                                </div>
                            @endif
                        </div>

                        <div data-reveal="{{ $loop->even ? 'left' : 'right' }}" @class(['lg:order-1' => $loop->even])>
                            <h3 class="font-display text-2xl font-semibold text-gray-900 sm:text-3xl">{{ $type->name }}</h3>
                            <p class="mt-2 flex items-center gap-1 text-sm text-gray-600">
                                <x-heroicon-o-users class="h-4 w-4" /> {{ $type->capacity }} {{ __('tamu') }}
                            </p>

                            @if ($type->localized_description)
                                <p class="mt-4 leading-relaxed text-gray-600">{{ str($type->localized_description)->limit(240) }}</p>
                            @endif

                            <p class="mt-4 text-sm text-gray-500">{{ __('Mulai dari') }}</p>
                            <p class="text-2xl font-semibold text-brand-700">
                                Rp {{ number_format($type->base_price, 0, ',', '.') }}
                                <span class="text-sm font-normal text-gray-500">/ {{ __('malam') }}</span>
                            </p>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <x-button :href="route('rooms.show', $type->slug)" variant="secondary">{{ __('Lihat detail') }}</x-button>
                                <x-button :href="route('availability.index')">{{ __('Pesan') }}</x-button>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-center text-gray-500">{{ __('Belum ada kamar tersedia.') }}</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- KONTAK --}}
    <section id="kontak" class="scroll-mt-16 py-16 sm:py-24">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 lg:grid-cols-2 lg:px-6">
            <div data-reveal="left">
                <p class="text-sm uppercase tracking-[0.3em] text-brand-600">{{ __('Kontak') }}</p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-gray-900 sm:text-4xl">{{ __('Hubungi Kami') }}</h2>
                <p class="mt-3 text-gray-600">{{ __('Kami siap membantu pertanyaan dan reservasi Anda.') }}</p>

                <ul class="mt-8 space-y-4 text-gray-700">
                    @if (config('villa.address'))
                        <li class="flex items-start gap-3">
                            <x-heroicon-o-map-pin class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" /> {{ config('villa.address') }}
                        </li>
                    @endif
                    @if (config('villa.phone'))
                        <li class="flex items-center gap-3">
                            <x-heroicon-o-phone class="h-5 w-5 shrink-0 text-brand-600" /> {{ config('villa.phone') }}
                        </li>
                    @endif
                    @if (config('villa.email'))
                        <li class="flex items-center gap-3">
                            <x-heroicon-o-envelope class="h-5 w-5 shrink-0 text-brand-600" /> {{ config('villa.email') }}
                        </li>
                    @endif
                </ul>

                <div class="mt-8 flex flex-wrap gap-3">
                    @if (config('villa.whatsapp'))
                        <x-button :href="'https://wa.me/' . config('villa.whatsapp') . '?text=' . rawurlencode(__('Halo, saya ingin bertanya tentang reservasi.'))"
                                  target="_blank" rel="noopener">
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
                            class="h-80 w-full rounded-3xl border-0 shadow-lg lg:h-full lg:min-h-96"
                            loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
                @else
                    <div class="flex h-80 w-full items-center justify-center rounded-3xl bg-brand-100 text-sm text-brand-700 lg:h-full">
                        {{ __('Peta belum diatur.') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
