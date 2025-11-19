<?php

namespace App\Mail;

use App\Models\GiftCard;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftCardCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $giftCard;

    public function __construct(GiftCard $giftCard)
    {
        $this->giftCard = $giftCard;
    }

    public function build()
    {
        return $this->subject('Gift Card Created Successfully')
                    ->markdown('emails.giftcards.created')
                    ->with([
                        'giftCard' => $this->giftCard,
                    ]);
    }
}