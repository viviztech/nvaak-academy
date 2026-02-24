<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enquiry_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')->constrained('enquiries')->cascadeOnDelete();
            $table->foreignId('followed_by')->constrained('users');
            $table->enum('follow_up_type', ['call', 'email', 'whatsapp', 'visit', 'sms'])->default('call');
            $table->text('notes')->nullable();
            $table->text('outcome')->nullable();
            $table->dateTime('next_follow_up_at')->nullable();
            $table->boolean('is_converted')->default(false);
            $table->timestamps();

            $table->index(['enquiry_id', 'next_follow_up_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiry_follow_ups');
    }
};
