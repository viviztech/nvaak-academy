<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ias_answer_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('batch_id')->constrained();
            $table->foreignId('exam_id')->nullable()->constrained('exams');
            $table->foreignId('subject_id')->constrained();
            $table->text('question_text');
            $table->enum('submission_type', ['answer_writing', 'essay', 'precis'])->default('answer_writing');
            $table->string('answer_file_path');
            $table->longText('answer_text')->nullable();
            $table->integer('word_count')->nullable();
            $table->integer('time_taken_minutes')->nullable();
            $table->timestamp('submitted_at');
            $table->enum('status', ['submitted', 'under_evaluation', 'evaluated', 'returned'])->default('submitted');
            $table->timestamps();
            $table->index(['student_id', 'batch_id', 'status'], 'ias_submissions_std_batch_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ias_answer_submissions');
    }
};
