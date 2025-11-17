<?php

namespace App\Mail;

use App\Models\UserStake;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StakeCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stake;

    public function __construct(UserStake $stake)
    {
        $this->stake = $stake;
    }

    public function build()
    {
        return $this->subject('Your Stake Was Successfully Created')
                    ->view('emails.stake.stake_created');
    }
}
