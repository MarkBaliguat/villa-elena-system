<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Models\Cart;
use App\Models\Unit;

class BookingCancelledEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $user;
    public $cart;
    public $units;
    public $cancellationReason;
    public $refundAmount;
    public $refundMethod;

    public function __construct(Booking $booking, $cancellationReason = null, $refundAmount = 0, $refundMethod = null)
    {
        $this->booking = $booking;
        $this->user = $booking->cart->user;
        $this->cart = $booking->cart;
        $this->units = $booking->cart->cartItems->map(function($item) {
            return $item->unit;
        });
        $this->cancellationReason = $cancellationReason;
        $this->refundAmount = $refundAmount;
        $this->refundMethod = $refundMethod;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Cancelled - Villa Elena Family Resort',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-cancelled',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}