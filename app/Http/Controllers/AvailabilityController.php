<?php

namespace App\Http\Controllers;

use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request, AvailabilityService $availability)
    {
        $results = null;
        $nights = null;

        if ($request->hasAny(['check_in', 'check_out', 'guests'])) {
            $data = $request->validate([
                'check_in' => ['required', 'date', 'after_or_equal:today'],
                'check_out' => ['required', 'date', 'after:check_in'],
                'guests' => ['required', 'integer', 'min:1', 'max:50'],
            ]);

            $checkIn = Carbon::parse($data['check_in'])->startOfDay();
            $checkOut = Carbon::parse($data['check_out'])->startOfDay();

            $nights = (int) $checkIn->diffInDays($checkOut);
            $results = $availability->availableTypes($checkIn, $checkOut, (int) $data['guests']);
        }

        return view('availability.index', compact('results', 'nights'));
    }
}
