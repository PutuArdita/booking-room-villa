<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomImage;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomImageController extends Controller
{
    public function store(Request $request, RoomType $roomType)
    {
        $request->validateWithBag('photos', [
            'photos' => ['required', 'array', 'max:10'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'photos.*.max' => 'Ukuran tiap foto maksimal 2 MB.',
            'photos.*.mimes' => 'Format foto harus jpg, png, atau webp.',
            'photos.*.image' => 'File harus berupa gambar.',
        ]);

        $next = ($roomType->images()->max('sort_order') ?? -1) + 1;

        foreach ($request->file('photos') as $file) {
            $path = $file->store("room-types/{$roomType->id}", 'public');
            $roomType->images()->create(['path' => $path, 'sort_order' => $next++]);
        }

        return back()->with('success', 'Foto diunggah.');
    }

    public function destroy(RoomImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Foto dihapus.');
    }
}
