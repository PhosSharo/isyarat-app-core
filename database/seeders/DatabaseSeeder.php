<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@bisindo.app'],
            [
                'name' => 'Admin',
                'email' => 'admin@bisindo.app',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'preferred_language' => 'bisindo',
            ]
        );

        // Researcher user (for ML pipeline)
        User::updateOrCreate(
            ['email' => 'researcher@bisindo.app'],
            [
                'name' => 'ML Researcher',
                'email' => 'researcher@bisindo.app',
                'password' => bcrypt('password'),
                'role' => 'researcher',
                'preferred_language' => 'bisindo',
            ]
        );

        // Test user
        User::updateOrCreate(
            ['email' => 'test@bisindo.app'],
            [
                'name' => 'Test User',
                'email' => 'test@bisindo.app',
                'password' => bcrypt('password'),
                'role' => 'user',
                'preferred_language' => 'bisindo',
            ]
        );

        $this->call([
            VocabularyCategorySeeder::class,
            SignVocabularySeeder::class,
            AiModelSeeder::class,
        ]);
    }
}
