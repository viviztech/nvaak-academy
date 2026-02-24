<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bank_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('chapter_id')->nullable()->constrained();
            $table->foreignId('topic_id')->nullable()->constrained();
            $table->unsignedBigInteger('subtopic_id')->nullable();
            $table->enum('question_type', ['mcq_single', 'mcq_multiple', 'numerical', 'match_following', 'assertion_reason', 'true_false'])->default('mcq_single');
            $table->text('question_text');
            $table->longText('question_text_html')->nullable();
            $table->string('question_image')->nullable();
            $table->json('options')->nullable();
            $table->json('correct_answer');
            $table->decimal('answer_range_from', 10, 4)->nullable();
            $table->decimal('answer_range_to', 10, 4)->nullable();
            $table->text('explanation')->nullable();
            $table->longText('explanation_html')->nullable();
            $table->string('explanation_image')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard', 'very_hard'])->default('medium');
            $table->integer('marks')->default(4);
            $table->decimal('negative_marks', 4, 2)->default(1.33);
            $table->json('tags')->nullable();
            $table->year('year_asked')->nullable();
            $table->enum('source', ['ncert', 'previous_year', 'custom', 'import'])->default('custom');
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['question_bank_id', 'subject_id', 'chapter_id'], 'questions_bank_subj_chap_idx');
            $table->index(['difficulty', 'question_type', 'is_active'], 'questions_diff_type_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
