<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('translation_history_id')->nullable()->constrained('translation_histories')->nullOnDelete();
            $table->foreignId('ai_model_id')->nullable()->constrained('ai_models')->nullOnDelete();
            $table->enum('type', ['correction', 'rating', 'bug_report', 'suggestion']);
            $table->boolean('is_correct')->nullable();         // Was the translation correct? (benar/salah)
            $table->text('expected_output')->nullable();        // What the correct output should have been
            $table->integer('rating')->nullable();              // 1-5 star rating
            $table->text('comment')->nullable();                // Free-text feedback
            $table->json('metadata')->nullable();               // Additional context data
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['ai_model_id', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_feedbacks');
    }
};
