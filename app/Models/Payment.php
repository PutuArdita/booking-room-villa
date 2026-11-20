<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const PENDING = 'pending';
    public const VERIFIED = 'verified';
    public const REJECTED = 'rejected';

    protected $fillable = [
        'booking_id',
        'amount',
        'proof_path',
        'status',
        'verified_by',
        'verified_at',
        'note',
    ];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime'];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::PENDING => __('Menunggu verifikasi'),
            self::VERIFIED => __('Terverifikasi'),
            self::REJECTED => __('Ditolak'),
            default => $this->status,
        };
    }
}
