<?php

namespace App\Mail;

use App\Models\UserStake;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StakeCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stake;
    public $reward;

    public function __construct(UserStake $stake, $reward)
    {
        $this->stake  = $stake;
        $this->reward = $reward;
    }

    public function build()
    {
        return $this->subject('Your Stake Has Been Completed')
                    ->view('emails.stake.completed');
    }
}
