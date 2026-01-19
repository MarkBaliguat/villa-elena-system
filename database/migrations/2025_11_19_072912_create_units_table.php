<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id('unitID');
            $table->string('unitName');
            $table->enum('unitType', ['room', 'cottage', 'special']);
            $table->text('description');
            $table->integer('capacity');
            $table->json('images')->nullable();
            $table->decimal('unitRatePrice', 10, 2);
            $table->enum('unitStatus', ['available', 'maintenance', 'blocked'])->default('available');
            $table->date('blockStartDate')->nullable();
            $table->date('blockEndDate')->nullable();
            $table->text('blockReason')->nullable();
            $table->boolean('for_special_events')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};