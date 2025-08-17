<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Change payment_method to enum
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'mobile_money', 'check', 'other'])->change();
            
            // Add new columns
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending')->after('payment_method');
            $table->string('reference_number')->nullable()->after('status');
            $table->text('notes')->nullable()->after('reference_number');
            
            // Add soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['status', 'reference_number', 'notes']);
            $table->string('payment_method')->change();
        });
    }
};
