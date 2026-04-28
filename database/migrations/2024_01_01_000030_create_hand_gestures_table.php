<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hand_gestures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocabulary_id')->constrained('sign_vocabularies')->cascadeOnDelete();
            $table->json('landmarks');                        // MediaPipe 21 landmarks x 2 hands (126d normalized)
            $table->enum('hand', ['left', 'right', 'both'])->default('both');
            $table->enum('gesture_type', ['static', 'dynamic'])->default('static');
            $table->integer('frame_count')->default(1);       // 1 for static, N for dynamic sequences
            $table->json('landmark_sequence')->nullable();     // For dynamic: array of landmark frames
            $table->float('confidence_score')->nullable();     // Confidence from ML model
            $table->string('source_dataset')->nullable();      // e.g. "asl_alphabet_kaggle", "roboflow_bisindov2"
            $table->string('contributor_id')->nullable();      // Who contributed this sample
            $table->timestamps();

            $table->index(['vocabulary_id', 'gesture_type']);
            $table->index('source_dataset');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hand_gestures');
    }
};
