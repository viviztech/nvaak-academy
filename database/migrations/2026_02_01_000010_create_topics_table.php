<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->integer('estimated_hours')->default(0);
            $table->decimal('neet_weightage_percent', 5, 2)->default(0);
            $table->enum('difficulty_level', ['easy', 'medium', 'hard', 'very_hard'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['chapter_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
