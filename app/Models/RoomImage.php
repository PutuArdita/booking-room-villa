<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RoomImage extends Model
{
    protected $fillable = ['room_type_id', 'path', 'sort_order'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn() => Storage::disk('public')->url($this->path));
    }
}
