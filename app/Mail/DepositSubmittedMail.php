<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\Deposit;

class DepositSubmittedMail extends Mailable
{
    public $deposit;

    public function __construct(Deposit $deposit)
    {
        $this->deposit = $deposit;
    }

    public function build()
    {
        return $this
            ->subject('Deposit Submitted - Pending Approval')
            ->markdown('emails.deposit-submitted')
            ->with([
                'deposit' => $this->deposit,
                'user' => $this->deposit->user,
                'crypto' => $this->deposit->cryptocurrency,
            ]);
    }
}
