<?php

namespace App\Mail;

use App\Models\AirdropClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AirdropClaimRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $claim;

    public function __construct(AirdropClaim $claim)
    {
        $this->claim = $claim;
    }

    public function build()
    {
        return $this->subject('Airdrop claim rejected')
                    ->view('emails.airdrop.rejected');
    }
}
