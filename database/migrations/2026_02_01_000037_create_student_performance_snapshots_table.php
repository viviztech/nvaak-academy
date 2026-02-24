<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_performance_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained();
            $table->date('snapshot_date');
            $table->enum('snapshot_type', ['weekly', 'monthly', 'exam_based'])->default('weekly');
            $table->decimal('overall_score_percent', 5, 2)->default(0);
            $table->integer('rank_in_batch')->nullable();
            $table->integer('rank_overall')->nullable();
            $table->smallInteger('predicted_neet_score')->nullable();
            $table->integer('predicted_neet_rank')->nullable();
            $table->json('subject_scores')->nullable();
            $table->json('strong_chapters')->nullable();
            $table->json('weak_chapters')->nullable();
            $table->decimal('attendance_percent', 5, 2)->default(0);
            $table->integer('exams_attempted')->default(0);
            $table->decimal('improvement_from_last', 5, 2)->nullable();
            $table->timestamps();
            $table->index(['student_id', 'batch_id', 'snapshot_date'], 'perf_snapshots_std_batch_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_performance_snapshots');
    }
};
