<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained();
            $table->string('name');
            $table->enum('course_type', ['neet', 'ias', 'both'])->default('neet');
            $table->foreignId('subject_id')->nullable()->constrained();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_type', 'subject_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_banks');
    }
};
