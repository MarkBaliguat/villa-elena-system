<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class SpecialBookingCancelledEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $user;
    public $cart;
    public $units;
    public $refundAmount;
    public $refundMethod;

    public function __construct(Booking $booking, $refundAmount = 0, $refundMethod = null)
    {
        $this->booking = $booking;
        $this->user = $booking->cart->user;
        $this->cart = $booking->cart;
        $this->units = $booking->cart->cartItems->map(function($item) {
            return $item->unit;
        });
        $this->refundAmount = $refundAmount;
        $this->refundMethod = $refundMethod;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Special Event Booking Cancelled - Villa Elena',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.special-booking-cancelled',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}