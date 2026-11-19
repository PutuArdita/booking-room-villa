<?php

namespace App\Http\Controllers;

use App\Models\RoomType;

class PublicRoomController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::bookable()
            ->with('images')
            ->orderBy('base_price')
            ->get();

        return view('rooms.index', compact('roomTypes'));
    }

    public function show(RoomType $roomType)
    {
        abort_unless($roomType->rooms()->where('is_active', true)->exists(), 404);

        $roomType->load('images');

        return view('rooms.show', compact('roomType'));
    }
}
