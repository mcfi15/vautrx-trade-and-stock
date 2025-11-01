<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;
    public $status;
    public $adminNotes;

    public function __construct($withdrawal, $status, $adminNotes = null)
    {
        $this->withdrawal = $withdrawal;
        $this->status = $status;
        $this->adminNotes = $adminNotes;
    }

    public function build()
    {
        return $this->subject('Your Withdrawal Request Status Updated')
            ->markdown('emails.status-update');
    }
}
