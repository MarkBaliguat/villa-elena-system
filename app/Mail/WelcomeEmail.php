<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct(User $user)
    {
        $this->user = $user;
        
        // FIXED: Use 'id' as the parameter name (Laravel expects this)
        // but pass userID as the value
        $this->verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $this->user->userID,  // Pass userID value
                'hash' => sha1($this->user->email)
            ]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Villa Elena - Verify Your Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}