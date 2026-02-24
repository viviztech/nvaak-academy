<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('institutes');
            $table->unsignedBigInteger('enquiry_id')->nullable();
            $table->foreign('enquiry_id')->references('id')->on('enquiries')->nullOnDelete();
            $table->string('application_number')->unique();
            $table->tinyInteger('application_step')->default(1);
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('blood_group')->nullable();
            $table->string('aadhaar_number')->nullable();
            $table->string('nationality')->default('Indian');
            $table->string('religion')->nullable();
            $table->enum('caste_category', ['general', 'obc', 'sc', 'st', 'ews'])->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->enum('course_applied', ['neet', 'ias'])->default('neet');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->foreign('batch_id')->references('id')->on('batches')->nullOnDelete();
            $table->string('previous_institution')->nullable();
            $table->enum('board', ['cbse', 'icse', 'state', 'other'])->nullable();
            $table->decimal('previous_percentage', 5, 2)->nullable();
            $table->smallInteger('year_of_passing')->nullable();
            $table->smallInteger('neet_previous_score')->nullable();
            $table->integer('neet_previous_rank')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('father_email')->nullable();
            $table->string('father_income')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_email')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->json('documents')->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'admitted', 'cancelled'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
            $table->text('admin_remarks')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->decimal('application_fee', 8, 2)->default(0);
            $table->boolean('application_fee_paid')->default(false);
            $table->string('payment_transaction_id')->nullable();
            $table->string('source')->nullable();
            $table->json('additional_info')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['batch_id']);
            $table->index(['course_applied']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
