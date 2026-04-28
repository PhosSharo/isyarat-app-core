<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audio_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocabulary_id')->nullable()->constrained('sign_vocabularies')->nullOnDelete();
            $table->string('file_path');                       // Path to audio file
            $table->string('mime_type')->default('audio/wav');
            $table->float('duration_seconds')->default(0);     // Durasi (detik)
            $table->float('file_size_mb')->default(0);         // Ukuran file (MB)
            $table->string('language', 10)->default('id');     // Audio language
            $table->text('transcript')->nullable();             // STT transcript of the audio
            $table->enum('type', ['tts_output', 'stt_input', 'reference'])->default('reference');
            $table->timestamps();

            $table->index(['vocabulary_id', 'language']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audio_files');
    }
};
