<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function facilities()
    {
        return view('pages.facilities');
    }

    public function gallery()
    {
        return view('pages.gallery');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
