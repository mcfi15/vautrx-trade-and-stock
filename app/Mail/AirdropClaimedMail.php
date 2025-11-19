<?php

namespace App\Mail;

use App\Models\Airdrop;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AirdropClaimedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $airdrop;
    public $claimAmount;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Airdrop $airdrop, $claimAmount)
    {
        $this->user = $user;
        $this->airdrop = $airdrop;
        $this->claimAmount = $claimAmount;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Airdrop Claim Submitted')
                    ->view('emails.airdrop.airdrop_claimed');
    }
}
