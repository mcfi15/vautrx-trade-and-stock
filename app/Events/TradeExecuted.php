<?php

namespace App\Events;

use App\Models\Trade;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TradeExecuted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $trade;

    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }

    public function broadcastOn(): Channel
    {
        // Broadcast to specific trading pair channel
        return new Channel('trades.' . $this->trade->trading_pair_id);
    }

    public function broadcastAs(): string
    {
        return 'trade.executed';
    }
}
