<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cartItemID');
            $table->foreignId('cartID')->constrained('carts', 'cartID');
            $table->foreignId('unitID')->constrained('units', 'unitID');
            $table->decimal('subtotalPrice', 10, 2);
            $table->boolean('isBooked')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};