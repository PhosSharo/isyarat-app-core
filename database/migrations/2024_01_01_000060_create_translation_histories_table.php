<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id')->index();             // Unique session identifier
            $table->enum('direction', ['sign_to_text', 'text_to_sign', 'speech_to_text', 'text_to_speech']);
            $table->text('input_data')->nullable();            // What was input (text, landmark ref, etc.)
            $table->text('output_data')->nullable();           // What was output
            $table->enum('input_language', ['bisindo', 'asl', 'id', 'en']);
            $table->enum('output_language', ['bisindo', 'asl', 'id', 'en']);
            $table->float('confidence_score')->nullable();     // ML confidence
            $table->float('duration_seconds')->nullable();     // How long the translation took
            $table->boolean('is_correct')->nullable();         // User-confirmed correctness
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('direction');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_histories');
    }
};
