<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;
    public $adminNotes;

    public function __construct($withdrawal, $adminNotes = null)
    {
        $this->withdrawal = $withdrawal;
        $this->adminNotes = $adminNotes;
    }

    public function build()
    {
        return $this->subject('Your Withdrawal Has Been Completed')
                    ->view('emails.withdrawal_completed')
                    ->with([
                        'withdrawal' => $this->withdrawal,
                        'adminNotes' => $this->adminNotes,
                    ]);
    }
}
