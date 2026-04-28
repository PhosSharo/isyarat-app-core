<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('text_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocabulary_id')->constrained('sign_vocabularies')->cascadeOnDelete();
            $table->string('source_language', 10);            // e.g. "id", "en"
            $table->string('target_language', 10);            // e.g. "en", "id"
            $table->text('source_text');                       // Original text
            $table->text('translated_text');                   // Translated text
            $table->integer('character_count')->default(0);    // Jumlah karakter / kata (string)
            $table->timestamps();

            $table->index(['source_language', 'target_language']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('text_translations');
    }
};
