<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Villa A</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-100">
    <form method="POST" action="{{ route('admin.login') }}"
          class="w-full max-w-sm space-y-4 rounded bg-white p-6 shadow">
        @csrf
        <h1 class="text-xl font-bold">Login Admin</h1>

        <div>
            <label class="block text-sm">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full rounded border p-2">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm">Password</label>
            <input type="password" name="password" required class="w-full rounded border p-2">
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember"> Ingat saya
        </label>

        <button class="w-full rounded bg-blue-600 p-2 text-white hover:bg-blue-700">Masuk</button>
    </form>
</body>
</html>
