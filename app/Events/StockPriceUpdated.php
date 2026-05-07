<?php

// app/Events/StockPriceUpdated.php
namespace App\Events;

use App\Models\Stock;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockPriceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stock;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function broadcastOn()
    {
        return new Channel('stocks');
    }

    public function broadcastWith()
    {
        return [
            'symbol' => $this->stock->symbol,
            'price' => number_format($this->stock->current_price, 2),
            'change' => number_format($this->stock->change_percentage, 2),
            'change_raw' => $this->stock->change,
            'last_updated' => $this->stock->last_updated->format('M j, H:i:s'),
        ];
    }
}