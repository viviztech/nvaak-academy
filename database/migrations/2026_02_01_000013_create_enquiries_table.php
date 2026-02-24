<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('institutes');
            $table->enum('source', ['walk_in', 'phone', 'website', 'social', 'referral'])->default('walk_in');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('course_interest', ['neet', 'ias', 'both'])->default('neet');
            $table->string('batch_interest')->nullable();
            $table->string('academic_background')->nullable();
            $table->string('previous_marks')->nullable();
            $table->string('current_school_college')->nullable();
            $table->text('query_notes')->nullable();
            $table->string('referral_name')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->enum('status', ['new', 'contacted', 'interested', 'not_interested', 'converted', 'lost'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('source_campaign')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'source']);
            $table->index(['assigned_to']);
            $table->index(['phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
