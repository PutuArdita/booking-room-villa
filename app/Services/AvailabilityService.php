<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomType;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /**
     * Kamar aktif yang kosong untuk malam check-in sampai sebelum check-out.
     * Dua rentang bentrok jika: mulai_lama < check_out_baru DAN selesai_lama > check_in_baru.
     */
    public function availableRooms(CarbonInterface $checkIn, CarbonInterface $checkOut): Builder
    {
        $in = $checkIn->toDateString();
        $out = $checkOut->toDateString();

        return Room::query()
            ->where('is_active', true)
            ->whereDoesntHave('bookings', function (Builder $q) use ($in, $out) {
                $q->blocking()
                    ->where('bookings.check_in', '<', $out)
                    ->where('bookings.check_out', '>', $in);
            })
            ->whereDoesntHave('blockedDates', function (Builder $q) use ($in, $out) {
                $q->where('start_date', '<', $out)
                    ->where('end_date', '>=', $in);
            });
    }

    /**
     * Tipe kamar yang muat untuk jumlah tamu dan masih punya kamar kosong,
     * masing-masing membawa relasi availableRooms.
     */
    public function availableTypes(CarbonInterface $checkIn, CarbonInterface $checkOut, int $guests): Collection
    {
        $rooms = $this->availableRooms($checkIn, $checkOut)->get()->groupBy('room_type_id');

        return RoomType::with('images')
            ->where('capacity', '>=', $guests)
            ->whereIn('id', $rooms->keys()->all())
            ->orderBy('base_price')
            ->get()
            ->each(fn($type) => $type->setRelation(
                'availableRooms',
                $rooms[$type->id]->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values()
            ));
    }
}
