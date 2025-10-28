<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\LoginHistory;

class LoginNotificationMail extends Mailable
{
    public $loginHistory;

    public function __construct(LoginHistory $loginHistory)
    {
        $this->loginHistory = $loginHistory;
    }

    public function build()
    {
        return $this
            ->subject('New Login Alert - ' . config('app.name'))
            ->markdown('emails.login-notification')
            ->with([
                'loginHistory' => $this->loginHistory,
                'user' => $this->loginHistory->user,
            ]);
    }
}