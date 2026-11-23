<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request, ReportService $reports)
    {
        $month = $this->month($request);

        return view('admin.reports.index', [
            'report' => $reports->monthly($month),
            'month' => $month,
            'prev' => $month->copy()->subMonth()->format('Y-m'),
            'next' => $month->copy()->addMonth()->format('Y-m'),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $start = $this->month($request);
        $end = $start->copy()->addMonth();

        $bookings = Booking::with('rooms.roomType')
            ->where('check_in', '<', $end->toDateString())
            ->where('check_out', '>', $start->toDateString())
            ->orderBy('check_in')
            ->get();

        return response()->streamDownload(function () use ($bookings) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8

            fputcsv($out, [
                'Kode',
                'Tamu',
                'Email',
                'HP',
                'Kamar',
                'Check-in',
                'Check-out',
                'Malam',
                'Total (Rp)',
                'Status',
                'Dibuat',
            ], ';', '"', '');

            foreach ($bookings as $booking) {
                $room = $booking->rooms->first();

                fputcsv($out, [
                    $booking->code,
                    $this->safe($booking->guest_name),
                    $this->safe($booking->guest_email),
                    $booking->guest_phone,
                    $this->safe($room ? $room->roomType->name . ' - ' . $room->name : ''),
                    $booking->check_in->format('Y-m-d'),
                    $booking->check_out->format('Y-m-d'),
                    (int) $booking->check_in->diffInDays($booking->check_out),
                    $booking->total_price,
                    Booking::statusText($booking->status),
                    $booking->created_at->format('Y-m-d H:i'),
                ], ';', '"', '');
            }

            fclose($out);
        }, 'reservasi-' . $start->format('Y-m') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function month(Request $request): Carbon
    {
        $request->validate(['month' => ['nullable', 'date_format:Y-m']]);

        return Carbon::createFromFormat('Y-m-d', ($request->input('month') ?? now()->format('Y-m')) . '-01')
            ->startOfDay();
    }

    /** Cegah formula Excel dari teks yang diketik tamu (misalnya diawali tanda =). */
    private function safe(?string $value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@\t\r]/', $value) ? "'" . $value : $value;
    }
}
