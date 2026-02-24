<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('syllabus_coverage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('chapter_id')->constrained('chapters');
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->foreignId('faculty_id')->constrained('users');
            $table->date('covered_on');
            $table->unsignedTinyInteger('coverage_percentage')->default(0);
            $table->text('notes')->nullable();
            $table->integer('class_duration_minutes')->default(0);
            $table->integer('students_present')->default(0);
            $table->timestamps();

            $table->index(['batch_id', 'chapter_id', 'covered_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syllabus_coverage');
    }
};
