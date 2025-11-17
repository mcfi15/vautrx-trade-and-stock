<?php

namespace App\Mail;

use App\Models\UserStake;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StakeRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stake;

    public function __construct(UserStake $stake)
    {
        $this->stake = $stake;
    }

    public function build()
    {
        return $this->subject('Your Stake Has Been Rejected')
                    ->view('emails.stake.rejected');
    }
}
