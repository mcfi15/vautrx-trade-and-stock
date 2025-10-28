<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;

class EmailVerificationMail extends Mailable
{
    public $user;
    public $verificationUrl;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->verificationUrl = URL::temporarySignedRoute(
            'email.verify',
            now()->addHours(24),
            ['token' => $token]
        );
    }

    public function build()
    {
        return $this
            ->subject('Verify Your Email Address')
            ->markdown('emails.email-verification')
            ->with([
                'user' => $this->user,
                'verificationUrl' => $this->verificationUrl,
            ]);
    }
}