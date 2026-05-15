<?php

return [
    'name' => env('APP_NAME', 'Ved Mitra'),
    'name_caps' => 'VED MITRA',
    'tagline' => 'Your Divine Spiritual Companion',
    'description' => 'Ved Mitra helps devotees book verified pandits, generate kundli insights, explore Hindu festivals, and read sacred Hindi spiritual texts in one premium devotional platform.',
    'keywords' => 'Ved Mitra, book pandit online, kundli generator, Hindu puja services, Vedic astrology, Hindu festivals, Hanuman Chalisa, Durga Chalisa, Shiv Chalisa, Satyanarayan Vrat Katha',
    'support_email' => env('SUPPORT_EMAIL', 'support@vedmitra.in'),
    'contact_phone' => env('SUPPORT_PHONE', '+91 98765 43210'),
    'address' => 'India',
    'audio' => [
        'gayatri' => env('GAYATRI_MANTRA_MP3', 'https://upload.wikimedia.org/wikipedia/commons/transcoded/1/11/Gayatri_mantra.ogg/Gayatri_mantra.ogg.mp3'),
        'gayatri_fallback' => 'https://upload.wikimedia.org/wikipedia/commons/1/11/Gayatri_mantra.ogg',
        'source' => 'https://commons.wikimedia.org/wiki/File:Gayatri_mantra.ogg',
    ],
    'images' => [
        'hero' => 'https://images.unsplash.com/photo-1763186534248-d0de60fd81e2?auto=format&fit=crop&w=2200&q=82',
        'puja' => 'https://images.unsplash.com/photo-1752667841694-b5898f685d0e?auto=format&fit=crop&w=1200&q=82',
        'yagya' => 'https://images.unsplash.com/photo-1763186534248-d0de60fd81e2?auto=format&fit=crop&w=1200&q=82',
        'temple' => 'https://images.unsplash.com/photo-1742277296672-e39540d72f71?auto=format&fit=crop&w=1200&q=82',
        'festival' => 'https://images.unsplash.com/photo-1776361159217-1ae6412847c1?auto=format&fit=crop&w=1200&q=82',
        'durga' => 'https://images.unsplash.com/photo-1729257983485-a3dd440a0611?auto=format&fit=crop&w=1200&q=82',
        'kundli' => 'https://images.unsplash.com/photo-1515942661900-94b3d1972591?auto=format&fit=crop&w=1200&q=82',
        'ebooks' => 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=1200&q=82',
        'pandit' => 'https://images.unsplash.com/photo-1752667841694-b5898f685d0e?auto=format&fit=crop&w=1200&q=82',
    ],
    'social' => [
        'instagram' => 'https://instagram.com/vedmitra',
        'facebook' => 'https://facebook.com/vedmitra',
        'youtube' => 'https://youtube.com/@vedmitra',
        'twitter' => 'https://x.com/vedmitra',
    ],
];
