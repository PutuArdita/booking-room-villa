<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('villa.name'))</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <script>
        document.documentElement.classList.add('js')
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-sand-50 font-sans text-gray-800 antialiased">
    @php($overlay = $__env->hasSection('transparent-nav'))

    <header id="site-header" class="site-header" data-mode="{{ $overlay ? 'overlay' : 'solid' }}">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 lg:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                @if (file_exists(public_path('images/logo.svg')))
                    <img src="{{ asset('images/logo.svg') }}" alt="" class="h-9 w-auto">
                @endif
                <span class="font-display text-xl font-semibold">{{ config('villa.name') }}</span>
            </a>

            <nav class="hidden items-center gap-6 text-sm md:flex">
                @include('layouts._nav-links')
                <x-button :href="route('availability.index')">{{ __('Cek Ketersediaan') }}</x-button>
            </nav>

            <button type="button" id="menu-toggle" aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu"
                class="rounded-lg p-2 hover:bg-black/10 md:hidden">
                <x-heroicon-o-bars-3 class="h-6 w-6" />
            </button>
        </div>

        <nav id="mobile-menu" class="hidden border-t border-gray-200 px-4 py-4 text-sm md:hidden">
            <div class="flex flex-col gap-4">
                @include('layouts._nav-links')
                <x-button :href="route('availability.index')" class="w-full">{{ __('Cek Ketersediaan') }}</x-button>
            </div>
        </nav>
    </header>

    <main @class(['pt-16' => !$overlay])>
        @if (session('success') || session('error'))
            <div class="mx-auto max-w-6xl space-y-2 px-4 pt-4">
                @if (session('success'))
                    <x-alert type="success">{{ session('success') }}</x-alert>
                @endif
                @if (session('error'))
                    <x-alert type="error">{{ session('error') }}</x-alert>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-brand-900 text-brand-100">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-3 lg:px-6">
            <div>
                <p class="font-display text-2xl font-semibold text-white">{{ config('villa.name') }}</p>
                <p class="mt-2 text-sm">{{ config('villa.tagline.' . app()->getLocale()) }}</p>
                {{-- <div class="mt-4 flex gap-4 text-sm">
                    @foreach (array_filter(config('villa.social', [])) as $name => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener"
                            class="capitalize hover:text-white">{{ $name }}</a>
                    @endforeach
                </div> --}}
                <div class="mt-5 flex gap-3">
                    @foreach (array_filter(config('villa.social', [])) as $name => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                            aria-label="{{ ucfirst($name) }}"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-brand-100/40 text-brand-100 transition hover:border-white hover:bg-white hover:text-brand-900">
                            <x-social-icon :name="$name" class="h-5 w-5" />
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <p class="font-semibold text-white">{{ __('Jelajahi') }}</p>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white">{{ __('Beranda') }}</a></li>
                    <li><a href="{{ route('rooms.index') }}" class="hover:text-white">{{ __('Kamar') }}</a></li>
                    <li><a href="{{ route('facilities.index') }}" class="hover:text-white">{{ __('Fasilitas') }}</a>
                    </li>
                    <li><a href="{{ route('gallery.index') }}" class="hover:text-white">{{ __('Galeri') }}</a></li>
                    <li><a href="{{ route('contact.index') }}" class="hover:text-white">{{ __('Kontak') }}</a></li>
                    <li><a href="{{ route('availability.index') }}"
                            class="hover:text-white">{{ __('Cek Ketersediaan') }}</a></li>
                </ul>
            </div>

            <div>
                <p class="font-semibold text-white">{{ __('Alamat') }}</p>
                <ul class="mt-3 space-y-2 text-sm">
                    @if (config('villa.address'))
                        <li>{{ config('villa.address') }}</li>
                    @endif
                    @if (config('villa.phone'))
                        <li>{{ config('villa.phone') }}</li>
                    @endif
                    @if (config('villa.email'))
                        <li>{{ config('villa.email') }}</li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs text-brand-100/70">
            &copy; {{ date('Y') }} {{ config('villa.name') }}. {{ __('Hak cipta dilindungi.') }}
        </div>
    </footer>
</body>

</html>
