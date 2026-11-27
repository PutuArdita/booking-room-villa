<?php

namespace App\Http\Controllers;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;

class PageController extends Controller
{
    public function facilities()
    {
        return view('pages.facilities');
    }


    public function contact()
    {
        return view('pages.contact');
    }

    public function gallery()
    {
        $categories = GalleryCategory::whereHas('images')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $images = GalleryImage::with('category')->latest('id')->get();

        return view('pages.gallery', compact('categories', 'images'));
    }
}
