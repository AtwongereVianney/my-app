<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable()->change();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('guest_access_token', 64)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique(['guest_access_token']);
            $table->dropColumn(['guest_name', 'guest_email', 'guest_phone', 'guest_access_token']);
            $table->unsignedBigInteger('student_id')->nullable(false)->change();
        });
    }
};


