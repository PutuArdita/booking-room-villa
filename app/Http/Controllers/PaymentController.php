<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function show(string $code)
    {
        $booking = Booking::with('payments')->where('code', $code)->firstOrFail();

        return view('booking.pay', compact('booking'));
    }

    public function store(Request $request, string $code)
    {
        $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:3072'],
        ], [
            'proof.required' => __('Pilih file bukti transfer.'),
            'proof.mimes' => __('Format file harus jpg, png, webp, atau pdf.'),
            'proof.max' => __('Ukuran file maksimal 3 MB.'),
        ]);

        $booking = Booking::where('code', $code)->firstOrFail();

        DB::transaction(function () use ($request, $booking) {
            // Kunci baris booking supaya klik ganda tidak membuat dua pembayaran
            $locked = Booking::whereKey($booking->id)->lockForUpdate()->first();

            $hasActive = $locked->payments()
                ->whereIn('status', [Payment::PENDING, Payment::VERIFIED])
                ->exists();

            if (!$locked->isPayable() || $hasActive) {
                throw ValidationException::withMessages([
                    'proof' => __('Booking ini tidak dapat menerima bukti pembayaran (sudah dibayar, sedang diproses, atau kedaluwarsa).'),
                ]);
            }

            $path = $request->file('proof')->store("payment-proofs/{$locked->id}", 'local');

            $locked->payments()->create([
                'amount' => $locked->total_price,
                'proof_path' => $path,
                'status' => Payment::PENDING,
            ]);
            $locked->statusLogs()->create([
                'from_status' => $locked->status,
                'to_status' => $locked->status,
                'note' => 'Bukti pembayaran diunggah',
            ]);
        });

        return redirect()
            ->route('booking.show', $code)
            ->with('success', __('Bukti pembayaran diunggah. Menunggu verifikasi admin.'));
    }
}
