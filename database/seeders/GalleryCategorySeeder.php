<?php

namespace Database\Seeders;

use App\Models\GalleryCategory;
use Illuminate\Database\Seeder;

class GalleryCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Kamar', 'Rooms', 'kamar'],
            ['Kolam', 'Pool', 'kolam'],
            ['Taman', 'Garden', 'taman'],
            ['Lobi', 'Lobby', 'lobi'],
        ] as $i => [$name, $nameEn, $slug]) {
            GalleryCategory::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'name_en' => $nameEn, 'sort_order' => $i + 1],
            );
        }
    }
}
