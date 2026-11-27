<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['gallery_category_id', 'path', 'width', 'height'];

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn() => asset('storage/' . $this->path));
    }
}
