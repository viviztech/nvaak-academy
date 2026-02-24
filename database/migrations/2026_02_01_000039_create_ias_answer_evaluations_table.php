<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ias_answer_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('ias_answer_submissions')->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users');
            $table->timestamp('evaluated_at');
            $table->integer('total_marks')->default(250);
            $table->decimal('marks_awarded', 5, 2)->default(0);
            $table->decimal('content_score', 4, 2)->default(0);
            $table->decimal('language_score', 4, 2)->default(0);
            $table->decimal('structure_score', 4, 2)->default(0);
            $table->decimal('analytical_score', 4, 2)->default(0);
            $table->text('general_feedback')->nullable();
            $table->text('strengths')->nullable();
            $table->text('improvements_needed')->nullable();
            $table->string('annotated_file_path')->nullable();
            $table->string('model_answer_path')->nullable();
            $table->boolean('is_returned_to_student')->default(false);
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
            $table->index(['submission_id', 'evaluator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ias_answer_evaluations');
    }
};
