@extends('layouts.admin')

@section('title', 'Kategori Galeri')

@section('content')
    <a href="{{ route('admin.gallery.index') }}" class="text-sm text-brand-700 hover:underline">&larr; Kembali ke galeri</a>
    <h1 class="mb-4 mt-2 text-2xl font-bold">Kategori Galeri</h1>

    @if ($errors->any())
        <ul class="mb-4 list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.gallery-categories.store') }}"
        class="mb-6 grid gap-3 rounded bg-white p-4 shadow sm:grid-cols-5">
        @csrf
        <div class="sm:col-span-2">
            <label class="block text-sm">Nama (Indonesia)</label>
            <input type="text" name="name" required maxlength="60" class="w-full rounded border p-2">
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm">Nama (English)</label>
            <input type="text" name="name_en" maxlength="60" class="w-full rounded border p-2">
        </div>
        <div>
            <label class="block text-sm">Urutan</label>
            <input type="number" name="sort_order" min="0" max="999" value="0" class="w-full rounded border p-2">
        </div>
        <div class="sm:col-span-5">
            <button class="rounded bg-brand-600 px-4 py-2 text-white hover:bg-brand-700">Tambah kategori</button>
        </div>
    </form>

    <div class="space-y-3">
        @foreach ($categories as $category)
            <div class="rounded bg-white p-4 shadow">
                <form method="POST" action="{{ route('admin.gallery-categories.update', $category) }}"
                    class="grid gap-3 sm:grid-cols-6 sm:items-end">
                    @csrf
                    @method('PUT')
                    <div class="sm:col-span-2">
                        <label class="block text-xs text-gray-500">Nama (Indonesia)</label>
                        <input type="text" name="name" value="{{ $category->name }}" required maxlength="60"
                            class="w-full rounded border p-2">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs text-gray-500">Nama (English)</label>
                        <input type="text" name="name_en" value="{{ $category->name_en }}" maxlength="60"
                            class="w-full rounded border p-2">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Urutan</label>
                        <input type="number" name="sort_order" value="{{ $category->sort_order }}" min="0" max="999"
                            class="w-full rounded border p-2">
                    </div>
                    <button class="rounded bg-brand-600 px-3 py-2 text-white hover:bg-brand-700">Simpan</button>
                </form>

                <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                    <span>{{ $category->images_count }} foto</span>
                    <form method="POST" action="{{ route('admin.gallery-categories.destroy', $category) }}"
                        onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection