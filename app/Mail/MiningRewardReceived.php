<?php

namespace App\Mail;

use App\Models\MiningReward;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MiningRewardReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $reward;

    public function __construct(MiningReward $reward)
    {
        $this->reward = $reward;
    }

    public function build()
    {
        return $this->subject('Mining Reward Received')
                    ->markdown('emails.mining.reward-received')
                    ->with([
                        'reward' => $this->reward,
                    ]);
    }
}