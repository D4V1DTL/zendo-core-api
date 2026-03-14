<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeAndVerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $verificationUrl;

    public function __construct(public User $user, string $token)
    {
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
        $this->verificationUrl = "{$frontendUrl}/verificar-email?token={$token}";
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido a Zendo — Verifica tu cuenta',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-verify',
            with: [
                'user'            => $this->user,
                'verificationUrl' => $this->verificationUrl,
            ],
        );
    }
}
