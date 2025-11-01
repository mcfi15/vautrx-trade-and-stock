<?php

namespace App\Mail;

use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;
    public $adminNotes;

    public function __construct(Deposit $deposit, $adminNotes)
    {
        $this->deposit = $deposit;
        $this->adminNotes = $adminNotes;
    }

    public function build()
    {
        return $this->subject('Your Deposit Request Was Rejected')
            ->view('emails.deposit_rejected');
    }
}
