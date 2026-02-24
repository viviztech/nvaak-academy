<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained();
            $table->foreignId('batch_id')->nullable()->constrained();
            $table->foreignId('subject_id')->nullable()->constrained();
            $table->foreignId('exam_series_id')->nullable()->constrained('exam_series');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->longText('instructions')->nullable();
            $table->json('rules')->nullable();
            $table->enum('exam_type', ['practice', 'chapter_test', 'unit_test', 'full_syllabus', 'mock_neet', 'mock_ias', 'previous_year'])->default('practice');
            $table->enum('course_type', ['neet', 'ias', 'both'])->default('neet');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('duration_minutes')->default(180);
            $table->decimal('total_marks', 6, 2)->default(720);
            $table->decimal('passing_marks', 6, 2)->default(360);
            $table->integer('total_questions')->default(180);
            $table->json('sections')->nullable();
            $table->boolean('negative_marking_enabled')->default(true);
            $table->decimal('negative_marks_per_wrong', 4, 2)->default(1.33);
            $table->boolean('partial_marking_enabled')->default(false);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('randomize_options')->default(false);
            $table->boolean('show_results_immediately')->default(true);
            $table->boolean('allow_review_after_submit')->default(true);
            $table->boolean('show_correct_answers')->default(true);
            $table->boolean('show_solutions')->default(true);
            $table->boolean('prevent_tab_switch')->default(false);
            $table->boolean('require_webcam')->default(false);
            $table->boolean('fullscreen_mode')->default(false);
            $table->integer('max_attempts')->default(1);
            $table->boolean('track_time_per_question')->default(true);
            $table->json('syllabus_coverage')->nullable();
            $table->enum('status', ['draft', 'published', 'live', 'completed', 'archived'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['batch_id', 'exam_type', 'course_type', 'status', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
