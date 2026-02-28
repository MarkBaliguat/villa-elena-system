<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'bookingID';

    protected $fillable = [
        'cartID',
        'totalPrice',
        'entranceFeeID', 
        'bookingStatus',
        'gcash_payment_intent_id',
        'bookingType',
        'eventType',
        'specialRequirements',
        'eventStartTime',
        'eventEndTime',
        'cancelledAt',
        'cancelledBy',
        'cancellationReason'
    ];

    protected $casts = [
        'totalPrice' => 'decimal:2',
        'eventStartTime' => 'datetime',
        'eventEndTime' => 'datetime',
        'cancelledAt' => 'datetime'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cartID', 'cartID');
    }

    public function cancelledByUser()
    {
        return $this->belongsTo(User::class, 'cancelledBy', 'userID');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'bookingID', 'bookingID');
    }

    /**
     * Get the entrance fee for the booking.
     */
    public function entranceFee()
    {
        return $this->belongsTo(EntranceFee::class, 'entranceFeeID', 'entranceFeeID');
    }
}