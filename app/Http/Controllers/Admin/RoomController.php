<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomRequest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::with('roomType')
            ->when($request->filled('room_type_id'), fn($q) => $q->where('room_type_id', $request->room_type_id))
            ->orderBy('room_type_id')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $roomTypes = RoomType::orderBy('name')->get();

        return view('admin.rooms.index', compact('rooms', 'roomTypes'));
    }

    public function create()
    {
        $roomTypes = RoomType::orderBy('name')->get();

        return view('admin.rooms.create', compact('roomTypes'));
    }

    public function store(RoomRequest $request)
    {
        Room::create($request->validated());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar ditambahkan.');
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::orderBy('name')->get();

        return view('admin.rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(RoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar diperbarui.');
    }

    public function destroy(Room $room)
    {
        // mencegah menghapus data kamar yang sudah pernah di booking
        if ($room->bookings()->exists()) {
            return back()->with('error', 'Kamar sudah pernah dipesan. Nonaktifkan saja, jangan dihapus.');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar dihapus.');
    }
}
