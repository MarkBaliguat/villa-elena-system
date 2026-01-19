<?php
// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'paymentID';

    protected $fillable = [
        'bookingID',
        'paymentReference',
        'paymentMethod',
        'paymentType',
        'amountPaid',
        'remainingBalance',
        'paymentDate',
        'paymentStatus',
        'isRefunded',
        'refundDate',
        'refundAmount',
        'refundReason'
        
    ];

    protected $casts = [
        'amountPaid' => 'decimal:2',
        'remainingBalance' => 'decimal:2',
        'refundAmount' => 'decimal:2',
        'paymentDate' => 'date',
        'refundDate' => 'date',
        'isRefunded' => 'boolean'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }
}