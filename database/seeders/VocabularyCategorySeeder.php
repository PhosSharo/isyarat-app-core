<?php

namespace Database\Seeders;

use App\Models\VocabularyCategory;
use Illuminate\Database\Seeder;

class VocabularyCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // BISINDO categories
            ['name' => 'Alfabet BISINDO', 'slug' => 'bisindo-alphabet', 'description' => 'Huruf A-Z dalam BISINDO', 'language' => 'bisindo', 'sort_order' => 1],
            ['name' => 'Angka BISINDO', 'slug' => 'bisindo-numbers', 'description' => 'Angka 0-9 dalam BISINDO', 'language' => 'bisindo', 'sort_order' => 2],
            ['name' => 'Kata Umum BISINDO', 'slug' => 'bisindo-common-words', 'description' => 'Kata-kata umum dalam BISINDO', 'language' => 'bisindo', 'sort_order' => 3],
            ['name' => 'Sapaan BISINDO', 'slug' => 'bisindo-greetings', 'description' => 'Sapaan dan salam dalam BISINDO', 'language' => 'bisindo', 'sort_order' => 4],
            ['name' => 'Darurat BISINDO', 'slug' => 'bisindo-emergency', 'description' => 'Isyarat darurat dalam BISINDO', 'language' => 'bisindo', 'sort_order' => 5],

            // ASL categories
            ['name' => 'ASL Alphabet', 'slug' => 'asl-alphabet', 'description' => 'Letters A-Z in ASL', 'language' => 'asl', 'sort_order' => 1],
            ['name' => 'ASL Numbers', 'slug' => 'asl-numbers', 'description' => 'Numbers 0-9 in ASL', 'language' => 'asl', 'sort_order' => 2],
            ['name' => 'ASL Common Words', 'slug' => 'asl-common-words', 'description' => 'Common words in ASL', 'language' => 'asl', 'sort_order' => 3],
            ['name' => 'ASL Greetings', 'slug' => 'asl-greetings', 'description' => 'Greetings and salutations in ASL', 'language' => 'asl', 'sort_order' => 4],
            ['name' => 'ASL Emergency', 'slug' => 'asl-emergency', 'description' => 'Emergency signs in ASL', 'language' => 'asl', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            VocabularyCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
