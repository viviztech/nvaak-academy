<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_structure_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('installment_number');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->timestamps();
            $table->index(['fee_structure_id', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_installments');
    }
};
