<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Format;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $categories = GalleryCategory::withCount('images')->orderBy('sort_order')->orderBy('name')->get();

        $images = GalleryImage::query()
            ->when($request->filled('kategori'), fn($q) => $q->where('gallery_category_id', $request->integer('kategori')))
            ->latest('id')
            ->paginate(24)
            ->withQueryString();

        return view('admin.gallery.index', compact('categories', 'images'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('upload', [
            'gallery_category_id' => ['required', 'exists:gallery_categories,id'],
            'photos' => ['required', 'array', 'max:10'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ], [
            'photos.max' => 'Maksimal 10 foto per unggahan.',
            'photos.*.max' => 'Ukuran tiap foto maksimal 8 MB.',
            'photos.*.mimes' => 'Format foto harus jpg, png, atau webp.',
            'photos.*.image' => 'File harus berupa gambar.',
        ]);

        $files = $request->file('photos');
        $failed = 0;

        foreach ($files as $file) {
            try {
                // $image = Image::read($file->getRealPath())->scaleDown(width: 1600, height: 1600);
                // $path = 'gallery/' . Str::uuid() . '.jpg';

                // Storage::disk('public')->put($path, $image->toJpeg(82)->toString());
                $image = Image::decodePath($file->getRealPath())->scaleDown(width: 1600, height: 1600);
                $path = 'gallery/' . Str::uuid() . '.jpg';

                Storage::disk('public')->put($path, (string) $image->encodeUsingFormat(Format::JPEG, quality: 82));

                GalleryImage::create([
                    'gallery_category_id' => $request->input('gallery_category_id'),
                    'path' => $path,
                    'width' => $image->width(),
                    'height' => $image->height(),
                ]);
            } catch (\Throwable $e) {
                report($e);
                $failed++;
            }
        }

        $done = count($files) - $failed;

        if ($done === 0) {
            return back()->with('error', 'Foto gagal diproses. Periksa log atau batas memori PHP.');
        }

        return back()->with('success', "{$done} foto diunggah." . ($failed ? " {$failed} gagal diproses." : ''));
    }

    public function update(Request $request, GalleryImage $image)
    {
        $data = $request->validate([
            'gallery_category_id' => ['required', 'exists:gallery_categories,id'],
        ]);

        $image->update($data);

        return back()->with('success', 'Kategori foto dipindahkan.');
    }

    public function destroy(GalleryImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Foto dihapus.');
    }
}
