<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('paymentID');
            $table->foreignId('bookingID')->constrained('bookings', 'bookingID');
            $table->string('paymentReference')->unique();
            $table->string('paymentMethod');
            $table->enum('paymentType', ['downpayment', 'full', 'remaining', 'refund']);
            $table->decimal('amountPaid', 10, 2);
            $table->decimal('remainingBalance', 10, 2)->default(0);
            $table->date('paymentDate');
            $table->enum('paymentStatus', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->boolean('isRefunded')->default(false);
            $table->date('refundDate')->nullable();
            $table->decimal('refundAmount', 10, 2)->nullable();
            $table->text('refundReason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};