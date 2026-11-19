{{-- message --}}
@if ($errors->any())
    <ul class="mb-4 list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm">Tipe kamar</label>
        <select name="room_type_id" required class="w-full rounded border p-2">
            <option value="">Pilih tipe</option>
            @foreach ($roomTypes as $type)
                <option value="{{ $type->id }}"
                    @selected(old('room_type_id', $room->room_type_id ?? '') == $type->id)>
                    {{ $type->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm">Nama / nomor kamar</label>
        <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}" required
               class="w-full rounded border p-2">
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
               @checked(old('is_active', $room->is_active ?? true))>
        Aktif (bisa dipesan)
    </label>

    <div class="flex gap-3">
        <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Simpan</button>
        <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Batal</a>
    </div>
</div>
