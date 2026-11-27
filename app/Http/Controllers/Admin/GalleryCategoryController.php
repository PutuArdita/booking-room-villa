<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryCategoryController extends Controller
{
    public function index()
    {
        $categories = GalleryCategory::withCount('images')->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.gallery.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        // Slug dibuat sekali saat kategori baru, tidak berubah saat diedit (URL tetap stabil)
        $base = Str::slug($data['name']) ?: 'kategori';
        $slug = $base;
        $i = 2;
        while (GalleryCategory::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        GalleryCategory::create($data + ['slug' => $slug]);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function update(Request $request, GalleryCategory $category)
    {
        $category->update($this->validated($request));

        return back()->with('success', 'Kategori diperbarui.');
    }

    public function destroy(GalleryCategory $category)
    {
        if ($category->images()->exists()) {
            return back()->with('error', 'Kategori masih memiliki foto. Pindahkan atau hapus fotonya dulu.');
        }

        $category->delete();

        return back()->with('success', 'Kategori dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'name_en' => ['nullable', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
