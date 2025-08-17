<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 10, 2);
        $table->date('payment_date');
        $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'mobile_money', 'check', 'other']);
        $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
        $table->string('reference_number')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes(); // Add soft deletes
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
