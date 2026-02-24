<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('fee_assignment_id')->constrained('student_fee_assignments');
            $table->foreignId('installment_id')->nullable()->constrained('fee_installments')->nullOnDelete();
            $table->string('receipt_number')->unique();
            $table->date('payment_date');
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_mode', ['cash', 'online', 'dd', 'cheque', 'upi'])->default('cash');
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('cheque_number')->nullable();
            $table->date('cheque_date')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['student_id', 'status', 'payment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
