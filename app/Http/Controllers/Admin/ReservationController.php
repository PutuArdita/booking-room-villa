<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\BookingStatusService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with('rooms.roomType', 'payments')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->input('q') . '%';
                $query->where(function ($w) use ($term) {
                    $w->where('code', 'like', $term)
                        ->orWhere('guest_name', 'like', $term)
                        ->orWhere('guest_email', 'like', $term)
                        ->orWhere('guest_phone', 'like', $term);
                });
            })
            ->when($request->input('status') === 'perlu_verifikasi', fn($query) =>
                $query->whereHas('payments', fn($p) => $p->where('status', Payment::PENDING)))
            ->when($request->filled('status') && $request->input('status') !== 'perlu_verifikasi', fn($query) =>
                $query->where('status', $request->input('status')))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reservations.index', compact('bookings'));
    }

    public function show(Booking $booking, BookingStatusService $statuses)
    {
        $booking->load('rooms.roomType', 'payments.verifier', 'statusLogs.user');
        $transitions = $statuses->allowed($booking);

        return view('admin.reservations.show', compact('booking', 'transitions'));
    }

    public function updateStatus(Request $request, Booking $booking, BookingStatusService $statuses)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([Booking::CHECKED_IN, Booking::CHECKED_OUT, Booking::CANCELLED])],
            'note' => [
                'nullable',
                'string',
                'max:500',
                Rule::requiredIf($request->input('status') === Booking::CANCELLED),
            ],
        ]);

        $statuses->transition($booking, $data['status'], $request->user(), $data['note'] ?? null);

        return back()->with('success', 'Status booking diperbarui.');
    }

    public function verifyPayment(Request $request, Payment $payment, BookingStatusService $statuses)
    {
        $statuses->verifyPayment($payment, $request->user());

        return back()->with('success', 'Pembayaran diverifikasi. Booking terkonfirmasi.');
    }

    public function rejectPayment(Request $request, Payment $payment, BookingStatusService $statuses)
    {
        $data = $request->validate(['note' => ['required', 'string', 'max:500']]);

        $statuses->rejectPayment($payment, $request->user(), $data['note']);

        return back()->with('success', 'Pembayaran ditolak. Tamu dapat mengunggah ulang bukti.');
    }

    public function proof(Payment $payment)
    {
        $path = storage_path('app/private/' . $payment->proof_path);

        abort_unless(is_file($path), 404);

        return response()->file($path, ['X-Content-Type-Options' => 'nosniff']);
    }
}
