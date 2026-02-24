<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained();
            $table->foreignId('batch_id')->nullable()->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('chapter_id')->nullable()->constrained();
            $table->foreignId('topic_id')->nullable()->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('material_type', ['pdf', 'video', 'audio', 'link', 'doc', 'ppt'])->default('pdf');
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->integer('file_size_kb')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_free_preview')->default(false);
            $table->enum('access_type', ['batch', 'course', 'all'])->default('batch');
            $table->string('thumbnail_path')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_published')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['batch_id', 'subject_id', 'chapter_id'], 'sm_batch_subj_chap_idx');
            $table->index(['material_type', 'is_published'], 'sm_type_published_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_materials');
    }
};
