<?php

return [
    'name' => env('VILLA_NAME', 'Villa A'),
    'tagline' => [
        'id' => 'Tempat menginap nyaman untuk liburan Anda.',
        'en' => 'A comfortable place to stay for your holiday.',
    ],
    'description' => [
        'id' => 'Deskripsi singkat villa. Ganti dengan teks asli.',
        'en' => 'Short villa description. Replace with the real text.',
    ],
    'address' => 'Alamat villa',
    'phone' => '',
    'email' => '',

    'payment_hours' => (int) env('VILLA_PAYMENT_HOURS', 24),
    'bank' => [
        'name' => env('VILLA_BANK_NAME', 'Nama Bank'),
        'account_number' => env('VILLA_BANK_ACCOUNT', '0000000000'),
        'account_holder' => env('VILLA_BANK_HOLDER', 'Nama Pemilik Rekening'),
    ],

    'hero_images' => ['images/hero/1.png', 'images/hero/2.png'],

    'about' => [
        'title' => ['id' => 'Tentang Villa A', 'en' => 'About Villa A'],
        'paragraphs' => [
            'id' => [
                'Paragraf pertama tentang villa Anda. Ganti dengan teks asli.',
                'Paragraf kedua: suasana, fasilitas, dan keunggulan villa.',
            ],
            'en' => [
                'First paragraph about your villa. Replace with the real text.',
                'Second paragraph: atmosphere, facilities, and what makes the villa special.',
            ],
        ],
        'highlights' => [
            ['icon' => 'map-pin', 'id' => 'Lokasi strategis', 'en' => 'Great location'],
            ['icon' => 'sparkles', 'id' => 'Suasana tenang dan asri', 'en' => 'Calm, green surroundings'],
            ['icon' => 'heart', 'id' => 'Pelayanan ramah', 'en' => 'Friendly service'],
        ],
    ],

    'whatsapp' => env('VILLA_WHATSAPP', ''),            // contoh: 6281234567890 (tanpa + dan tanpa 0 di depan)
    'maps_url' => env('VILLA_MAPS_URL', ''),            // tautan "Buka di Google Maps"
    'maps_embed_url' => env('VILLA_MAPS_EMBED_URL', ''), // nilai src dari kode sematan peta
    'social' => [
        'instagram' => env('VILLA_INSTAGRAM', ''),
        'facebook' => env('VILLA_FACEBOOK', ''),
        'tiktok' => env('VILLA_TIKTOK', ''),
    ],

    'hours' => [
        'id' => 'Resepsionis buka setiap hari, 08.00 - 21.00 WITA',
        'en' => 'Front desk open daily, 08:00 - 21:00 WITA',
    ],

    'facilities' => [
        [
            'icon' => 'sun',
            'image' => 'images/facilities/pool.jpg', // opsional, kosongkan ('') jika belum ada foto
            'name' => ['id' => 'Kolam Renang', 'en' => 'Swimming Pool'],
            'description' => [
                'id' => 'Deskripsi singkat fasilitas. Ganti dengan teks asli.',
                'en' => 'Short facility description. Replace with the real text.',
            ],
        ],
        [
            'icon' => 'wifi',
            'image' => '',
            'name' => ['id' => 'Wi-Fi Gratis', 'en' => 'Free Wi-Fi'],
            'description' => [
                'id' => 'Deskripsi singkat fasilitas. Ganti dengan teks asli.',
                'en' => 'Short facility description. Replace with the real text.',
            ],
        ],
        [
            'icon' => 'sparkles',
            'image' => '',
            'name' => ['id' => 'Layanan Kebersihan', 'en' => 'Housekeeping'],
            'description' => [
                'id' => 'Deskripsi singkat fasilitas. Ganti dengan teks asli.',
                'en' => 'Short facility description. Replace with the real text.',
            ],
        ],
        [
            'icon' => 'home-modern',
            'image' => '',
            'name' => ['id' => 'Area Santai', 'en' => 'Lounge Area'],
            'description' => [
                'id' => 'Deskripsi singkat fasilitas. Ganti dengan teks asli.',
                'en' => 'Short facility description. Replace with the real text.',
            ],
        ],
        [
            'icon' => 'key',
            'image' => '',
            'name' => ['id' => 'Check-in Fleksibel', 'en' => 'Flexible Check-in'],
            'description' => [
                'id' => 'Deskripsi singkat fasilitas. Ganti dengan teks asli.',
                'en' => 'Short facility description. Replace with the real text.',
            ],
        ],
        [
            'icon' => 'tv',
            'image' => '',
            'name' => ['id' => 'TV Layar Datar', 'en' => 'Flat-screen TV'],
            'description' => [
                'id' => 'Deskripsi singkat fasilitas. Ganti dengan teks asli.',
                'en' => 'Short facility description. Replace with the real text.',
            ],
        ],
    ],
];
