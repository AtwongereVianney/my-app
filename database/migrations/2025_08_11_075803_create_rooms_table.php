<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->string('room_number')->unique();
        $table->integer('capacity');
        $table->enum('type', ['single', 'double', 'suite']);
        $table->boolean('is_available')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
