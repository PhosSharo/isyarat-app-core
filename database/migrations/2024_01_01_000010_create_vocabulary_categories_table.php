<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vocabulary_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // e.g. "Alphabet", "Numbers", "Greetings"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('language', ['bisindo', 'asl']);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['language', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vocabulary_categories');
    }
};
