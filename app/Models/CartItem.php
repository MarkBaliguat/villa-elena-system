<?php
// app/Models/CartItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'cartItemID';

    protected $fillable = [
        'cartID',
        'unitID',
        'subtotalPrice',
        'isBooked', 
        
    ];

    protected $casts = [
        'subtotalPrice' => 'decimal:2'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cartID', 'cartID');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unitID', 'unitID');
    }
}