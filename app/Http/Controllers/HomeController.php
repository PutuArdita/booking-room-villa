<?php

namespace App\Http\Controllers;

use App\Models\RoomType;

class HomeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::bookable()
            ->with('images')
            ->orderBy('base_price')
            ->take(3)
            ->get();

        return view('home', compact('roomTypes'));
    }
}
