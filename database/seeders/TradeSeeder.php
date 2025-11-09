<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trade;
use App\Models\Order;
use App\Models\User;
use App\Models\TradingPair;
use Illuminate\Support\Str;

class TradeSeeder extends Seeder
{
    public function run()
    {
        $pair = TradingPair::first();
        $order = Order::first();
        $buyer = User::first();
        $seller = User::skip(1)->first();

        if (! $pair || ! $order || ! $buyer || ! $seller) {
            $this->command->warn("⚠️ TradeSeeder skipped — Missing required data.");
            $this->command->warn("🧙 Run seeders for users, pairs & orders first.");
            return;
        }

        $this->command->info("✅ Seeding trades for Trading Pair ID: {$pair->id}");

        $prices = [35000.42, 34989.10, 35020.88, 35005.12];
        $quantities = [0.0025, 0.0018, 0.0031, 0.004];

        foreach ($prices as $key => $price) {
            $qty = $quantities[$key];
            $total = $price * $qty;

            Trade::create([
                'trade_number'       => strtoupper(Str::random(10)),
                'order_id'           => $order->id,
                'trading_pair_id'    => $pair->id,   // ✅ auto-detect correct ID
                'buyer_id'           => $buyer->id,
                'seller_id'          => $seller->id,
                'price'              => $price,
                'quantity'           => $qty,
                'total_amount'       => $total,
                'buyer_fee'          => 0.0005,
                'seller_fee'         => 0.0005,
                'blockchain_tx_hash' => Str::random(64),
                'blockchain_status'  => 'pending',
                'created_at'         => now()->subSeconds(rand(1,50)),
                'updated_at'         => now(),
            ]);
        }

        $this->command->info("🎯 TradeSeeder completed successfully!");
    }
}
