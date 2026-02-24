<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('material_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('study_materials')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamp('accessed_at');
            $table->boolean('completed')->default(false);
            $table->tinyInteger('progress_percent')->default(0);
            $table->string('device_type')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['material_id', 'student_id', 'accessed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_access_logs');
    }
};
