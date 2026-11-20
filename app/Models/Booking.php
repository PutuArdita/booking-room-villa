<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public const PENDING = 'pending_payment';
    public const CONFIRMED = 'confirmed';
    public const CHECKED_IN = 'checked_in';
    public const CHECKED_OUT = 'checked_out';
    public const REJECTED = 'rejected';
    public const CANCELLED = 'cancelled';
    public const EXPIRED = 'expired';

    protected $fillable = [
        'code',
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in',
        'check_out',
        'guests_count',
        'total_price',
        'status',
        'expires_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'expires_at' => 'datetime',
        ];
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'booking_rooms')
            ->withPivot('price_per_night')
            ->withTimestamps();
    }

    /**
     * Booking yang sedang mengunci kamar: terkonfirmasi, check-in,
     * atau menunggu pembayaran yang belum lewat batas waktu.
     */
    public function scopeBlocking($query)
    {
        return $query->where(function ($q) {
            $q->whereIn('bookings.status', [self::CONFIRMED, self::CHECKED_IN])
                ->orWhere(function ($q) {
                    $q->where('bookings.status', self::PENDING)
                        ->where('bookings.expires_at', '>', now());
                });
        });
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status === self::PENDING && $this->expires_at?->isPast()) {
            return __('Kedaluwarsa');
        }

        return match ($this->status) {
            self::PENDING => __('Menunggu pembayaran'),
            self::CONFIRMED => __('Terkonfirmasi'),
            self::CHECKED_IN => __('Check-in'),
            self::CHECKED_OUT => __('Check-out'),
            self::REJECTED => __('Ditolak'),
            self::CANCELLED => __('Dibatalkan'),
            self::EXPIRED => __('Kedaluwarsa'),
            default => $this->status,
        };
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isPayable(): bool
    {
        return $this->status === self::PENDING && $this->expires_at?->isFuture();
    }
}
