<?php

return [
    'title' => env('APP_NAME', 'Sistem Informasi'),
    'description' => 'Aplikasi resmi internal instansi pemerintahan.',
    'image' => env('APP_URL') . '/images/seo-default.png',
    'url' => env('APP_URL', 'http://localhost'),

    'twitter' => [
        'site' => '@instansi_pemda',
        'card' => 'summary_large_image',
    ],

    'og' => [
        'type' => 'website',
        'site_name' => 'Instansi Pemerintah',
    ],
];
