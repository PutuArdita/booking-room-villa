@extends('layouts.admin')

@section('title', 'Kamar')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Kamar</h1>
        <a href="{{ route('admin.rooms.create') }}"
           class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Tambah</a>
    </div>

    <form method="GET" class="mb-4 flex items-center gap-2 text-sm">
        <select name="room_type_id" class="rounded border p-2" onchange="this.form.submit()">
            <option value="">Semua tipe</option>
            @foreach ($roomTypes as $type)
                <option value="{{ $type->id }}" @selected(request('room_type_id') == $type->id)>
                    {{ $type->name }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Nama / nomor</th>
                    <th class="p-3">Tipe</th>
                    <th class="p-3">Status</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rooms as $room)
                    <tr class="border-t">
                        <td class="p-3">{{ $room->name }}</td>
                        <td class="p-3">{{ $room->roomType->name }}</td>
                        <td class="p-3">
                            @if ($room->is_active)
                                <span class="rounded bg-green-100 px-2 py-1 text-green-800">Aktif</span>
                            @else
                                <span class="rounded bg-gray-200 px-2 py-1 text-gray-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="flex gap-3 p-3">
                            <a href="{{ route('admin.rooms.edit', $room) }}"
                               class="text-blue-600 hover:underline">Ubah</a>
                            <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                                  onsubmit="return confirm('Hapus kamar ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-3 text-center text-gray-500">Belum ada kamar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $rooms->links() }}</div>
@endsection
