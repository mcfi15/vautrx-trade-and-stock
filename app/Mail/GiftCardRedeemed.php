<?php

namespace App\Mail;

use App\Models\GiftCard;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftCardRedeemed extends Mailable
{
    use Queueable, SerializesModels;

    public $giftCard;
    public $type;

    public function __construct(GiftCard $giftCard, $type = 'redeemer')
    {
        $this->giftCard = $giftCard;
        $this->type = $type;
    }

    public function build()
    {
        if ($this->type === 'redeemer') {
            return $this->subject('Gift Card Redeemed Successfully')
                        ->markdown('emails.giftcards.redeemed-redeemer')
                        ->with([
                            'giftCard' => $this->giftCard,
                        ]);
        } else {
            return $this->subject('Your Gift Card Was Redeemed')
                        ->markdown('emails.giftcards.redeemed-creator')
                        ->with([
                            'giftCard' => $this->giftCard,
                        ]);
        }
    }
}