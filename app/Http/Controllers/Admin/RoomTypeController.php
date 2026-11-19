<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomTypeRequest;
use App\Models\RoomType;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->orderBy('name')->paginate(10);

        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('admin.room-types.create');
    }

    public function store(RoomTypeRequest $request)
    {
        RoomType::create($request->validated());

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar ditambahkan.');
    }

    public function edit(RoomType $roomType)
    {
        return view('admin.room-types.edit', compact('roomType'));
    }

    public function update(RoomTypeRequest $request, RoomType $roomType)
    {
        $roomType->update($request->validated());

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar diperbarui.');
    }

    public function destroy(RoomType $roomType)
    {
        if ($roomType->rooms()->exists()) {
            return back()->with('error', 'Tipe kamar masih punya kamar, hapus kamarnya dulu.');
        }

        $roomType->delete();

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar dihapus.');
    }
}
