<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('bookingID');
            $table->foreignId('cartID')->constrained('carts', 'cartID');
            $table->foreignId('entranceFeeID')->nullable(); 
            $table->decimal('totalPrice', 10, 2);
            $table->enum('bookingStatus', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->string('gcash_payment_intent_id')->nullable();
            $table->enum('paymentStatus', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('bookingType', ['day-use', 'overnight', 'special-event']);
            $table->enum('eventType', ['normal-booking', 'birthday', 'anniversary', 'wedding', 'corporate', 'christening','reunion','other'])->default('normal-booking');
            $table->text('specialRequirements')->nullable();
            $table->timestamp('eventStartTime')->nullable();
            $table->timestamp('eventEndTime')->nullable();
            $table->timestamp('cancelledAt')->nullable();
            $table->foreignId('cancelledBy')->nullable()->constrained('users', 'userID');
            $table->text('cancellationReason')->nullable();
            $table->timestamps();
        }
    
    );
        
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};