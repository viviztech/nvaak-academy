<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained();
            $table->foreignId('batch_id')->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('fee_type', ['tuition', 'registration', 'exam', 'material', 'hostel', 'other'])->default('tuition');
            $table->decimal('total_amount', 10, 2);
            $table->boolean('installments_allowed')->default(false);
            $table->tinyInteger('installment_count')->nullable();
            $table->json('installment_plan')->nullable();
            $table->date('valid_from');
            $table->date('valid_to')->nullable();
            $table->decimal('late_fee_per_day', 8, 2)->default(0);
            $table->boolean('discount_allowed')->default(false);
            $table->decimal('max_discount_percent', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['batch_id', 'fee_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
