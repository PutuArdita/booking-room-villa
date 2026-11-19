@if ($errors->any())
    <ul class="mb-4 list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm">Nama</label>
        <input type="text" name="name" value="{{ old('name', $roomType->name ?? '') }}" required
            class="w-full rounded border p-2">
    </div>

    <div>
        <label class="block text-sm">Slug (kosongkan untuk otomatis)</label>
        <input type="text" name="slug" value="{{ old('slug', $roomType->slug ?? '') }}"
            class="w-full rounded border p-2">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm">Kapasitas (tamu)</label>
            <input type="number" name="capacity" min="1"
                value="{{ old('capacity', $roomType->capacity ?? '') }}" required class="w-full rounded border p-2">
        </div>
        <div>
            <label class="block text-sm">Harga dasar per malam (Rp)</label>
            <input type="number" name="base_price" min="0"
                value="{{ old('base_price', $roomType->base_price ?? '') }}" required class="w-full rounded border p-2">
        </div>
    </div>

    <div>
        <label class="block text-sm">Deskripsi (Indonesia)</label>
        <textarea name="description" rows="3" class="w-full rounded border p-2">{{ old('description', $roomType->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm">Deskripsi (English)</label>
        <textarea name="description_en" rows="3" class="w-full rounded border p-2">{{ old('description_en', $roomType->description_en ?? '') }}</textarea>
    </div>

    <div class="flex gap-3">
        <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Simpan</button>
        <a href="{{ route('admin.room-types.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Batal</a>
    </div>
</div>
