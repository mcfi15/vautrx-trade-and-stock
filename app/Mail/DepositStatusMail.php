<?php

namespace App\Mail;

use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;
    public $status;
    public $adminNotes;

    public function __construct(Deposit $deposit, $status, $adminNotes = null)
    {
        $this->deposit = $deposit;
        $this->status = $status;
        $this->adminNotes = $adminNotes;
    }

    public function build()
    {
        return $this->subject('Your Deposit Has Been ' . ucfirst($this->status))
            ->markdown('emails.deposit-status')
            ->with([
                'deposit' => $this->deposit,
                'statusText' => ucfirst($this->status),
                'adminNotes' => $this->adminNotes,
            ]);
    }
}
