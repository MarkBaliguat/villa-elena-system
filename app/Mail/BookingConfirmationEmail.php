<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $user;
    public $cart;
    public $units;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->user = $booking->cart->user;
        $this->cart = $booking->cart;
        $this->units = $booking->cart->cartItems->map(function($item) {
            return $item->unit;
        });
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmation - Villa Elena Family Resort',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}