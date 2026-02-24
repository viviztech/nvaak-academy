<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained();
            $table->decimal('total_marks', 6, 2)->default(0);
            $table->decimal('marks_obtained', 6, 2)->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('wrong_answers')->default(0);
            $table->integer('unattempted')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->integer('rank_in_batch')->nullable();
            $table->integer('rank_overall')->nullable();
            $table->decimal('percentile', 5, 2)->nullable();
            $table->enum('pass_fail', ['pass', 'fail'])->default('fail');
            $table->integer('time_taken_seconds')->default(0);
            $table->json('subject_wise_scores')->nullable();
            $table->json('chapter_wise_scores')->nullable();
            $table->json('strength_areas')->nullable();
            $table->json('weakness_areas')->nullable();
            $table->boolean('is_published')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->index(['exam_id', 'student_id', 'batch_id', 'rank_in_batch']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
