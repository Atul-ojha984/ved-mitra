<?php

namespace Database\Seeders;

use App\Models\Ebook;
use Illuminate\Database\Seeder;

class EbookLibrarySeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('spiritual_library.books') as $book) {
            Ebook::updateOrCreate(
                ['title' => $book['title']],
                [
                    'category' => $book['category'],
                    'description' => $book['description'],
                    'cover_image' => $book['cover_image'],
                    'content' => json_encode([
                        'chapters' => $book['chapters'],
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
                    'is_published' => true,
                    'file_url' => null,
                ]
            );
        }
    }
}
