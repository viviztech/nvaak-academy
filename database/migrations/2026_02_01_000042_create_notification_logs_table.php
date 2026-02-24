<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notification_type');
            $table->enum('channel', ['email', 'sms', 'whatsapp', 'in_app', 'push'])->default('in_app');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->json('metadata')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending');
            $table->json('provider_response')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['notifiable_type', 'notifiable_id', 'status'], 'notif_logs_type_id_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
