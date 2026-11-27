@extends('layouts.public')

@section('title', __('Galeri') . ' - ' . config('villa.name'))

@section('content')
    @include('layouts._page-header', [
        'title' => __('Galeri'),
        'subtitle' => __('Momen dan sudut terbaik dari villa kami.'),
    ])

    @if ($images->isEmpty())
        <p class="mx-auto max-w-7xl px-4 py-16 text-center text-gray-500 lg:px-6">{{ __('Galeri akan segera tersedia.') }}</p>
    @else
        {{-- Tab kategori --}}
        <div class="sticky top-[60px] z-30 border-b border-sand-100 bg-sand-50/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-4 py-3 sm:justify-center lg:px-6" role="group"
                aria-label="{{ __('Kategori foto') }}">
                <button type="button" data-filter="all" class="chip is-active" aria-pressed="true">{{ __('Semua') }}</button>
                @foreach ($categories as $category)
                    <button type="button" data-filter="{{ $category->slug }}" class="chip" aria-pressed="false">
                        {{ $category->label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Masonry --}}
        <div id="gallery" class="mx-auto max-w-7xl px-4 py-8 lg:px-6">
            <div class="columns-1 gap-4 sm:columns-2 lg:columns-3">
                @foreach ($images as $image)
                    <div data-gallery-item data-category="{{ $image->category->slug }}" data-full="{{ $image->url }}"
                        data-alt="{{ $image->category->label }} - {{ config('villa.name') }}" class="mb-4 break-inside-avoid">
                        <button type="button" aria-label="{{ __('Perbesar foto') }}"
                            class="group block w-full overflow-hidden rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                            <img src="{{ $image->url }}" width="{{ $image->width }}" height="{{ $image->height }}"
                                alt="{{ $image->category->label }} - {{ config('villa.name') }}" loading="lazy"
                                class="h-auto w-full transition duration-500 group-hover:scale-105">
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Lightbox --}}
        <div id="lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90" role="dialog"
            aria-modal="true" aria-label="{{ __('Galeri') }}">
            <button type="button" id="lightbox-close" aria-label="{{ __('Tutup') }}"
                class="absolute right-4 top-4 rounded-full bg-white/10 p-2 text-white hover:bg-white/20">
                <x-heroicon-o-x-mark class="h-6 w-6" />
            </button>
            <button type="button" id="lightbox-prev" aria-label="{{ __('Sebelumnya') }}"
                class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20">
                <x-heroicon-o-chevron-left class="h-6 w-6" />
            </button>
            <button type="button" id="lightbox-next" aria-label="{{ __('Berikutnya') }}"
                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20">
                <x-heroicon-o-chevron-right class="h-6 w-6" />
            </button>
            <img id="lightbox-img" src="" alt="" class="max-h-[88vh] max-w-[92vw] object-contain">
            <p id="lightbox-count" class="absolute bottom-4 text-sm text-white/80"></p>
        </div>
    @endif
@endsection