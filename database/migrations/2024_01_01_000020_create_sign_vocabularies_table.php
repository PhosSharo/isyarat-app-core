<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sign_vocabularies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('vocabulary_categories')->cascadeOnDelete();
            $table->string('word');                           // The word/letter/phrase
            $table->text('description')->nullable();          // How to perform the sign
            $table->enum('language', ['bisindo', 'asl']);
            $table->enum('type', ['alphabet', 'number', 'word', 'phrase']);
            $table->string('image_path')->nullable();         // Reference image of the sign
            $table->string('video_path')->nullable();         // Reference video clip
            $table->integer('word_count')->default(1);        // Jumlah kata (kata/item)
            $table->integer('character_count')->default(0);   // Jumlah karakter (string length)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['language', 'type']);
            $table->index(['word', 'language']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sign_vocabularies');
    }
};
