<?php

namespace Database\Seeders;

use App\Models\SignVocabulary;
use App\Models\VocabularyCategory;
use Illuminate\Database\Seeder;

class SignVocabularySeeder extends Seeder
{
    public function run(): void
    {
        // BISINDO Alphabet (A-Z) - seeded from app_disabilitas reference
        $bisindoAlphabet = VocabularyCategory::where('slug', 'bisindo-alphabet')->first();
        if ($bisindoAlphabet) {
            foreach (range('A', 'Z') as $letter) {
                SignVocabulary::updateOrCreate(
                    ['word' => $letter, 'language' => 'bisindo', 'type' => 'alphabet'],
                    [
                        'category_id' => $bisindoAlphabet->id,
                        'description' => "Isyarat BISINDO untuk huruf {$letter}",
                        'is_active' => true,
                    ]
                );
            }
        }

        // BISINDO Numbers (0-9)
        $bisindoNumbers = VocabularyCategory::where('slug', 'bisindo-numbers')->first();
        if ($bisindoNumbers) {
            for ($i = 0; $i <= 9; $i++) {
                SignVocabulary::updateOrCreate(
                    ['word' => (string) $i, 'language' => 'bisindo', 'type' => 'number'],
                    [
                        'category_id' => $bisindoNumbers->id,
                        'description' => "Isyarat BISINDO untuk angka {$i}",
                        'is_active' => true,
                    ]
                );
            }
        }

        // BISINDO Common Words (from app_disabilitas 13 words)
        $bisindoWords = VocabularyCategory::where('slug', 'bisindo-common-words')->first();
        if ($bisindoWords) {
            $words = [
                'Apa' => 'Isyarat untuk kata "Apa"',
                'Bagaimana' => 'Isyarat untuk kata "Bagaimana"',
                'Baik' => 'Isyarat untuk kata "Baik"',
                'Bisa' => 'Isyarat untuk kata "Bisa"',
                'Halo' => 'Isyarat untuk kata "Halo"',
                'Kamu' => 'Isyarat untuk kata "Kamu"',
                'Makan' => 'Isyarat untuk kata "Makan"',
                'Mau' => 'Isyarat untuk kata "Mau"',
                'Minum' => 'Isyarat untuk kata "Minum"',
                'Nama' => 'Isyarat untuk kata "Nama"',
                'Saya' => 'Isyarat untuk kata "Saya"',
                'Terima Kasih' => 'Isyarat untuk kata "Terima Kasih"',
                'Tolong' => 'Isyarat untuk kata "Tolong"',
            ];

            foreach ($words as $word => $desc) {
                SignVocabulary::updateOrCreate(
                    ['word' => $word, 'language' => 'bisindo', 'type' => 'word'],
                    [
                        'category_id' => $bisindoWords->id,
                        'description' => $desc,
                        'is_active' => true,
                    ]
                );
            }
        }

        // ASL Alphabet (A-Z)
        $aslAlphabet = VocabularyCategory::where('slug', 'asl-alphabet')->first();
        if ($aslAlphabet) {
            foreach (range('A', 'Z') as $letter) {
                SignVocabulary::updateOrCreate(
                    ['word' => $letter, 'language' => 'asl', 'type' => 'alphabet'],
                    [
                        'category_id' => $aslAlphabet->id,
                        'description' => "ASL sign for letter {$letter}",
                        'is_active' => true,
                    ]
                );
            }
        }

        // ASL Numbers (0-9)
        $aslNumbers = VocabularyCategory::where('slug', 'asl-numbers')->first();
        if ($aslNumbers) {
            for ($i = 0; $i <= 9; $i++) {
                SignVocabulary::updateOrCreate(
                    ['word' => (string) $i, 'language' => 'asl', 'type' => 'number'],
                    [
                        'category_id' => $aslNumbers->id,
                        'description' => "ASL sign for number {$i}",
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
