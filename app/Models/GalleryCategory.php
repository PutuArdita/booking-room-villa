<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
    protected $fillable = ['name', 'name_en', 'slug', 'sort_order'];

    public function images()
    {
        return $this->hasMany(GalleryImage::class);
    }

    protected function label(): Attribute
    {
        return Attribute::get(fn() => app()->getLocale() === 'en' && filled($this->name_en)
            ? $this->name_en
            : $this->name);
    }
}
