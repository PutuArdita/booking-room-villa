@extends('layouts.admin')

@section('title', 'Ubah Tipe Kamar')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Ubah Tipe Kamar</h1>
    <form method="POST" action="{{ route('admin.room-types.update', $roomType) }}"
        class="max-w-xl rounded bg-white p-6 shadow">
        @csrf
        @method('PUT')
        @include('admin.room-types._form')
    </form>
    <div class="mt-6 max-w-xl rounded bg-white p-6 shadow">
        <h2 class="mb-4 text-lg font-bold">Foto</h2>

        @if ($errors->photos->any())
            <ul class="mb-4 list-disc rounded bg-red-100 p-3 pl-6 text-sm text-red-800">
                @foreach ($errors->photos->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('admin.room-types.images.store', $roomType) }}" enctype="multipart/form-data"
            class="mb-4 space-y-2">
            @csrf
            <input type="file" name="photos[]" multiple required accept="image/jpeg,image/png,image/webp"
                class="block w-full text-sm">
            <p class="text-xs text-gray-500">Maksimal 10 file per unggahan, 2 MB per foto (jpg, png, webp).</p>
            <button class="rounded bg-brand-600 px-4 py-2 text-white hover:bg-brand-700">Unggah</button>
        </form>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
            @forelse ($roomType->images as $image)
                <div class="space-y-1">
                    <img src="{{ $image->url }}" alt="Foto {{ $roomType->name }}" class="h-28 w-full rounded object-cover">
                    <form method="POST" action="{{ route('admin.room-images.destroy', $image) }}"
                        onsubmit="return confirm('Hapus foto ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="col-span-full text-sm text-gray-500">Belum ada foto.</p>
            @endforelse
        </div>
    </div>
@endsection