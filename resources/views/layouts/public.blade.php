<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('villa.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800">
    <header class="border-b">
        <div class="mx-auto flex max-w-6xl items-center justify-between p-4">
            <a href="{{ route('home') }}" class="text-lg font-bold">{{ config('villa.name') }}</a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('home') }}" class="hover:underline">{{ __('Beranda') }}</a>
                <a href="{{ route('rooms.index') }}" class="hover:underline">{{ __('Kamar') }}</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('locale', 'id') }}" @class(['hover:underline', 'font-bold' => app()->getLocale() === 'id'])>ID</a>
                <a href="{{ route('locale', 'en') }}" @class(['hover:underline', 'font-bold' => app()->getLocale() === 'en'])>EN</a>
            </nav>
        </div>
    </header>

    {{-- <main>@yield('content')</main> --}}
    <main>
        @if (session('success') || session('error'))
            <div class="mx-auto max-w-6xl px-4 pt-4">
                @if (session('success'))
                    <div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="rounded bg-red-100 p-3 text-red-800">{{ session('error') }}</div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-10 border-t">
        <div class="mx-auto max-w-6xl p-4 text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ config('villa.name') }}. {{ __('Hak cipta dilindungi.') }}
        </div>
    </footer>
</body>
</html>
