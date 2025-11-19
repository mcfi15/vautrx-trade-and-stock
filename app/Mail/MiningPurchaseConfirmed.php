<?php

namespace App\Mail;

use App\Models\UserMiningMachine;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MiningPurchaseConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $machine;

    public function __construct(UserMiningMachine $machine)
    {
        $this->machine = $machine;
    }

    public function build()
    {
        return $this->subject('Mining Purchase Confirmed')
                    ->markdown('emails.mining.purchase-confirmed')
                    ->with([
                        'machine' => $this->machine,
                    ]);
    }
}