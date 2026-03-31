<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cartID');
            $table->foreignId('user_id')->constrained('users', 'userID');
            $table->date('checkInDate');
            $table->date('checkOutDate');
            $table->integer('daysCount')->default(1);
            // $table->integer('numGuests');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};