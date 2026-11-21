@extends('layouts.admin')

@section('title', 'Akun Saya')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Akun Saya</h1>

    <div class="grid gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('admin.account.profile') }}" class="space-y-4 rounded bg-white p-6 shadow">
            @csrf
            @method('PUT')
            <h2 class="font-bold">Profil</h2>

            @if ($errors->profile->any())
                <ul class="list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
                    @foreach ($errors->profile->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div>
                <label class="block text-sm">Nama</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                    class="w-full rounded border p-2">
            </div>
            <p class="text-sm text-gray-500">Email: {{ auth()->user()->email }} &middot; Peran: {{ auth()->user()->role }}
            </p>

            <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Simpan</button>
        </form>

        <form method="POST" action="{{ route('admin.account.password') }}" class="space-y-4 rounded bg-white p-6 shadow">
            @csrf
            @method('PUT')
            <h2 class="font-bold">Ganti password</h2>

            @if ($errors->password->any())
                <ul class="list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
                    @foreach ($errors->password->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div>
                <label class="block text-sm">Password saat ini</label>
                <input type="password" name="current_password" required class="w-full rounded border p-2">
            </div>
            <div>
                <label class="block text-sm">Password baru (minimal 8 karakter)</label>
                <input type="password" name="password" required class="w-full rounded border p-2">
            </div>
            <div>
                <label class="block text-sm">Ulangi password baru</label>
                <input type="password" name="password_confirmation" required class="w-full rounded border p-2">
            </div>

            <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Ganti Password</button>
        </form>
    </div>
@endsection