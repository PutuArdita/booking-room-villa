@extends('layouts.admin')

@section('title', 'Tipe Kamar')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Tipe Kamar</h1>
        <a href="{{ route('admin.room-types.create') }}"
            class="rounded bg-brand-600 px-4 py-2 text-white hover:bg-brand-700">Tambah</a>
    </div>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Kapasitas</th>
                    <th class="p-3">Harga/malam</th>
                    <th class="p-3">Jumlah kamar</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roomTypes as $type)
                    <tr class="border-t">
                        <td class="p-3">{{ $type->name }}</td>
                        <td class="p-3">{{ $type->capacity }} tamu</td>
                        <td class="p-3">Rp {{ number_format($type->base_price, 0, ',', '.') }}</td>
                        <td class="p-3">{{ $type->rooms_count }}</td>
                        <td class="flex gap-3 p-3">
                            <a href="{{ route('admin.room-types.edit', $type) }}"
                                class="text-brand-600 hover:underline">Ubah</a>
                            <form method="POST" action="{{ route('admin.room-types.destroy', $type) }}"
                                onsubmit="return confirm('Hapus tipe kamar ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-3 text-center text-gray-500">Belum ada tipe kamar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $roomTypes->links() }}</div>
@endsection