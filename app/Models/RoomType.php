<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'description_en',
        'capacity',
        'base_price',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeBookable($query)
    {
        return $query->whereHas('rooms', fn($q) => $q->where('is_active', true));
    }

    protected function localizedDescription(): Attribute
    {
        return Attribute::get(function () {
            return app()->getLocale() === 'en' && filled($this->description_en)
                ? $this->description_en
                : $this->description;
        });
    }
}
