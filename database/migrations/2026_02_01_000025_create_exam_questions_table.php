<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('exam_sections')->nullOnDelete();
            $table->integer('question_order')->default(0);
            $table->decimal('marks', 4, 2)->default(4);
            $table->decimal('negative_marks', 4, 2)->default(1.33);
            $table->timestamps();
            $table->unique(['exam_id', 'question_id']);
            $table->index(['exam_id', 'section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
