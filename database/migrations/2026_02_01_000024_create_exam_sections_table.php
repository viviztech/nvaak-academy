<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('display_order')->default(0);
            $table->integer('total_questions')->default(0);
            $table->decimal('marks_per_correct', 4, 2)->default(4);
            $table->decimal('negative_marks', 4, 2)->default(1.33);
            $table->integer('min_questions_to_attempt')->nullable();
            $table->timestamps();
            $table->index('exam_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sections');
    }
};
