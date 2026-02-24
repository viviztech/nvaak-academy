<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('institute_id')->nullable()->after('id');
            $table->string('phone')->nullable()->after('email');
            $table->string('profile_photo_path')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('profile_photo_path');
            $table->timestamp('last_login_at')->nullable()->after('is_active');

            $table->foreign('institute_id')->references('id')->on('institutes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['institute_id']);
            $table->dropColumn(['institute_id', 'phone', 'profile_photo_path', 'is_active', 'last_login_at']);
        });
    }
};
