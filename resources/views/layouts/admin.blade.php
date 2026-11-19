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
                <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="text-red-600 hover:underline">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-6xl p-4">
        @yield('content')
    </main>
</body>
</html>
