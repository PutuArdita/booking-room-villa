@extends('layouts.admin')

@section('title', 'Pengguna')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Pengguna</h1>
        <a href="{{ route('admin.users.create') }}"
            class="rounded bg-brand-600 px-4 py-2 text-white hover:bg-brand-700">Tambah</a>
    </div>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Peran</th>
                    <th class="p-3">Status</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td class="p-3">
                            {{ $user->name }}
                            @if ($user->is(auth()->user()))
                                <span class="text-gray-500">(Anda)</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">{{ $user->role }}</td>
                        <td class="p-3">
                            @if ($user->is_active)
                                <span class="rounded bg-green-100 px-2 py-1 text-xs text-green-800">Aktif</span>
                            @else
                                <span class="rounded bg-gray-200 px-2 py-1 text-xs text-gray-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="flex gap-3 p-3">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-brand-600 hover:underline">Ubah</a>
                            @unless ($user->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}"
                                    onsubmit="return confirm('Ubah status akun ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button class="{{ $user->is_active ? 'text-red-600' : 'text-green-700' }} hover:underline">
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection