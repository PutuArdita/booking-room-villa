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
];
