<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained();
            $table->tinyInteger('attempt_number')->default(1);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->integer('time_taken_seconds')->nullable();
            $table->enum('status', ['started', 'submitted', 'evaluated', 'absent'])->default('started');
            $table->string('ip_address')->nullable();
            $table->text('browser_info')->nullable();
            $table->boolean('is_proctored')->default(false);
            $table->json('proctoring_flags')->nullable();
            $table->timestamps();
            $table->unique(['exam_id', 'student_id', 'attempt_number']);
            $table->index(['exam_id', 'student_id', 'status', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_exams');
    }
};
