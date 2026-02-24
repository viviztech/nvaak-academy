<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained();
            $table->json('given_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('marks_awarded', 5, 2)->default(0);
            $table->integer('time_spent_seconds')->default(0);
            $table->boolean('is_marked_for_review')->default(false);
            $table->boolean('is_skipped')->default(false);
            $table->dateTime('answered_at')->nullable();
            $table->timestamps();
            $table->index(['student_exam_id', 'question_id', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
