<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('institutes');
            $table->enum('course_type', ['neet', 'ias'])->default('neet');
            $table->enum('batch_type', [
                'foundation', 'target', 'crash_course', 'repeater',
                'weekend', 'prelims', 'mains', 'integrated',
            ]);
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('academic_year');
            $table->enum('medium', ['english', 'tamil', 'bilingual'])->default('english');
            $table->integer('max_strength')->default(60);
            $table->integer('current_strength')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('schedule_days')->nullable();
            $table->time('schedule_time_from')->nullable();
            $table->time('schedule_time_to')->nullable();
            $table->string('class_room')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('coordinator_id')->nullable();
            $table->foreign('coordinator_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['course_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
