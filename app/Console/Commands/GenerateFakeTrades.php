<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Trade;
use App\Events\TradeExecuted;
use Str;

class GenerateFakeTrades extends Command
{
    protected $signature = 'trade:simulate {pairId}';
    protected $description = 'Generate fake trades every few seconds';

    public function handle()
    {
        $pairId = $this->argument('pairId');

        while (true) {
            $price = rand(25000, 35000) + (rand(0, 999999)/100000);
            $qty = rand(1, 100) / 100;
            $total = $price * $qty;

            $fakeTrade = Trade::create([
                'trade_number' => strtoupper(Str::random(10)),
                'order_id' => 1, 
                'trading_pair_id' => $pairId,
                'buyer_id' => 1,
                'seller_id' => 2,
                'price' => $price,
                'quantity' => $qty,
                'total_amount' => $total,
                'buyer_fee' => 0.001 * $qty,
                'seller_fee' => 0.001 * $qty,
                'blockchain_tx_hash' => null,
                'blockchain_status' => 'pending',
            ]);

            // broadcast this fake trade
            event(new TradeExecuted($fakeTrade));

            $this->info("Fake Trade: $price | $qty");
            sleep(rand(2, 6)); // simulate random trade timing
        }
    }
}
