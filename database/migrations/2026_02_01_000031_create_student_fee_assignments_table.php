<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_fee_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained();
            $table->foreignId('fee_structure_id')->constrained();
            $table->enum('concession_type', ['none', 'merit', 'sc_st', 'sibling', 'staff', 'custom'])->default('none');
            $table->decimal('concession_amount', 10, 2)->default(0);
            $table->text('concession_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('final_amount', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['student_id', 'batch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fee_assignments');
    }
};
