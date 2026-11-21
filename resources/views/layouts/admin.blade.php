<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - Villa A</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">
    <nav class="bg-white shadow">
        <div class="mx-auto flex max-w-6xl items-center justify-between p-4">
            <a href="{{ route('admin.dashboard') }}" class="font-bold">Villa A Admin</a>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('admin.calendar') }}" class="hover:underline">Kalender</a>
                <a href="{{ route('admin.blocked-dates.index') }}" class="hover:underline">Blokir</a>
                <a href="{{ route('admin.reservations.index') }}" class="hover:underline">Reservasi</a>
                <a href="{{ route('admin.room-types.index') }}" class="hover:underline">Tipe Kamar</a>
                <a href="{{ route('admin.rooms.index') }}" class="hover:underline">Kamar</a>
                {{-- <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span> --}}
                @if (auth()->user()->role === 'owner')
                    <a href="{{ route('admin.users.index') }}" class="hover:underline">Pengguna</a>
                @endif
                <a href="{{ route('admin.account.edit') }}" class="hover:underline">{{ auth()->user()->name }}
                    ({{ auth()->user()->role }})</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="text-red-600 hover:underline">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-6xl p-4">
        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded bg-red-100 p-3 text-red-800">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</body>

</html>