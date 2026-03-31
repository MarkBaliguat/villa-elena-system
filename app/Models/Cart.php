<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'cartID';

    protected $fillable = [
        'user_id',
        'checkInDate',
        'checkOutDate',
        'daysCount',
        // 'numGuests',
        'is_active'
    ];

    protected $casts = [
        'checkInDate' => 'date',
        'checkOutDate' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userID');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'cartID', 'cartID');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'cartID', 'cartID');
    }
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cartID', 'cartID');
    }
}