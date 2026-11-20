<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatusLog extends Model
{
    protected $fillable = ['booking_id', 'from_status', 'to_status', 'user_id', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
