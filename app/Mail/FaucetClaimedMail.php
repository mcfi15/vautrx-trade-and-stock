<?php

namespace App\Mail;

use App\Models\Faucet;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FaucetClaimedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $faucet;
    public $amount;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Faucet $faucet, $amount)
    {
        $this->user = $user;
        $this->faucet = $faucet;
        $this->amount = $amount;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Faucet Claimed Successfully')
                    ->view('emails.faucet_claimed');
    }
}
