<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_type_id', 'name', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_rooms')
            ->withPivot('price_per_night')
            ->withTimestamps();
    }

    public function blockedDates()
    {
        return $this->hasMany(BlockedDate::class);
    }
}
