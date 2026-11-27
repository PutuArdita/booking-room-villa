@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <h1 class="text-2xl font-bold">Galeri</h1>
        <a href="{{ route('admin.gallery-categories.index') }}"
            class="rounded border bg-white px-4 py-2 text-sm hover:bg-gray-50">Kelola kategori</a>
    </div>

    @if ($categories->isEmpty())
        <p class="rounded bg-yellow-50 p-3 text-sm text-yellow-800">
            Belum ada kategori. Buat kategori dulu lewat tombol "Kelola kategori".
        </p>
    @else
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data"
            class="mb-6 space-y-3 rounded bg-white p-4 shadow">
            @csrf
            <h2 class="font-bold">Unggah foto</h2>

            @if ($errors->upload->any())
                <ul class="list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
                    @foreach ($errors->upload->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="grid gap-3 sm:grid-cols-3">
                <div>
                    <label class="block text-sm">Kategori</label>
                    <select name="gallery_category_id" required class="w-full rounded border p-2">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('gallery_category_id', request('kategori')) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm">Foto (maksimal 10 file, 8 MB per foto)</label>
                    <input type="file" name="photos[]" multiple required accept="image/jpeg,image/png,image/webp"
                        class="block w-full text-sm">
                </div>
            </div>

            <button class="rounded bg-brand-600 px-4 py-2 text-white hover:bg-brand-700">Unggah</button>
        </form>
    @endif

    <div class="mb-4 flex flex-wrap gap-2 text-sm">
        <a href="{{ route('admin.gallery.index') }}"
            class="rounded-full border px-3 py-1 {{ request('kategori') ? 'bg-white' : 'bg-brand-600 text-white' }}">Semua</a>
        @foreach ($categories as $category)
            <a href="{{ route('admin.gallery.index', ['kategori' => $category->id]) }}"
                class="rounded-full border px-3 py-1 {{ request('kategori') == $category->id ? 'bg-brand-600 text-white' : 'bg-white' }}">
                {{ $category->name }} ({{ $category->images_count }})
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
        @forelse ($images as $image)
            <div class="overflow-hidden rounded bg-white shadow">
                <img src="{{ $image->url }}" alt="" loading="lazy" class="aspect-square w-full object-cover">
                <div class="space-y-2 p-2 text-sm">
                    <form method="POST" action="{{ route('admin.gallery.update', $image) }}">
                        @csrf
                        @method('PATCH')
                        <select name="gallery_category_id" onchange="this.form.submit()"
                            class="w-full rounded border p-1 text-sm">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($image->gallery_category_id === $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <form method="POST" action="{{ route('admin.gallery.destroy', $image) }}"
                        onsubmit="return confirm('Hapus foto ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-sm text-gray-500">Belum ada foto.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $images->links() }}</div>
@endsection