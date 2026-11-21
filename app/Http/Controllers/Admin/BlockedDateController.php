<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BlockedDateController extends Controller
{
    public function index()
    {
        $blocks = BlockedDate::with('room.roomType')
            ->orderByDesc('start_date')
            ->paginate(15);

        return view('admin.blocked-dates.index', compact('blocks'));
    }

    public function create()
    {
        $rooms = Room::with('roomType')->orderBy('room_type_id')->orderBy('name')->get();

        return view('admin.blocked-dates.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $room = Room::whereKey($data['room_id'])->lockForUpdate()->firstOrFail();

            $conflict = $room->bookings()->blocking()
                ->where('bookings.check_in', '<=', $data['end_date'])
                ->where('bookings.check_out', '>', $data['start_date'])
                ->exists();

            if ($conflict) {
                throw ValidationException::withMessages([
                    'start_date' => 'Ada booking aktif pada rentang tanggal ini. Batalkan booking itu dulu.',
                ]);
            }

            $room->blockedDates()->create(Arr::only($data, ['start_date', 'end_date', 'reason']));
        });

        return redirect()->route('admin.blocked-dates.index')
            ->with('success', 'Tanggal diblokir.');
    }

    public function destroy(BlockedDate $blockedDate)
    {
        $blockedDate->delete();

        return back()->with('success', 'Blokir dihapus.');
    }
}
