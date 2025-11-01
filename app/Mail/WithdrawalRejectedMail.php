<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;
    public $adminNotes;

    public function __construct($withdrawal, $adminNotes)
    {
        $this->withdrawal = $withdrawal;
        $this->adminNotes = $adminNotes;
    }

    public function build()
    {
        return $this->subject('Your Withdrawal Request Was Rejected')
                    ->view('emails.withdrawal_rejected')
                    ->with([
                        'withdrawal' => $this->withdrawal,
                        'adminNotes' => $this->adminNotes,
                    ]);
    }
}
