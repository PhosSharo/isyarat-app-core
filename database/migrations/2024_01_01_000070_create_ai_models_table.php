<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');                            // e.g. "asl_alphabet_v1", "bisindo_words_v2"
            $table->string('version');                         // Semantic version
            $table->enum('type', ['alphabet_classifier', 'word_classifier', 'stt_model', 'tts_model']);
            $table->enum('language', ['bisindo', 'asl', 'id', 'en', 'universal']);
            $table->string('file_path')->nullable();           // Path to .tflite or model file
            $table->float('file_size_mb')->nullable();         // Model file size
            $table->float('accuracy_percent')->nullable();     // Tingkat akurasi (%)
            $table->integer('num_classes')->nullable();         // Number of output classes
            $table->json('training_config')->nullable();       // Hyperparams, architecture details
            $table->json('metrics')->nullable();               // Precision, recall, F1, confusion matrix summary
            $table->integer('training_samples')->nullable();   // Number of training samples used
            $table->integer('validation_samples')->nullable();
            $table->enum('status', ['training', 'validating', 'ready', 'deployed', 'archived'])->default('training');
            $table->boolean('is_active')->default(false);      // Currently deployed model
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['name', 'version']);
            $table->index(['type', 'language', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_models');
    }
};
