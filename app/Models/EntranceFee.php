<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntranceFee extends Model
{
    use HasFactory;

    protected $table = 'entrance_fees';
    protected $primaryKey = 'entranceFeeID';

    protected $fillable = [
        'feeName',
        'amount',
        'isActive'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'isActive' => 'boolean'
    ];

    /**
     * Get the bookings for the entrance fee.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'entranceFeeID', 'entranceFeeID');
    }

    /**
     * Scope active fees
     */
    public function scopeActive($query)
    {
        return $query->where('isActive', true);
    }
}