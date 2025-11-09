<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\TradingPair;
use App\Models\User;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // assign orders to first user
        $pairs = TradingPair::all();

        foreach ($pairs as $pair) {

            // ✅ 10 BUY orders
            for ($i = 1; $i <= 10; $i++) {
                $price = rand(90, 110) + (mt_rand(0, 999999) / 1000000);

                $qty = rand(1, 5) + (mt_rand(0, 999999) / 1000000);

                Order::create([
                    'order_number'       => strtoupper(Str::random(12)),
                    'user_id'            => $user->id,
                    'trading_pair_id'    => $pair->id,
                    'type'               => 'limit',
                    'side'               => 'buy',
                    'price'              => $price,
                    'quantity'           => $qty,
                    'remaining_quantity' => $qty,
                    'total_amount'       => $price * $qty,
                    'status'             => 'pending'
                ]);
            }

            // ✅ 10 SELL orders
            for ($i = 1; $i <= 10; $i++) {
                $price = rand(110, 130) + (mt_rand(0, 999999) / 1000000);
                $qty = rand(1, 5) + (mt_rand(0, 999999) / 1000000);

                Order::create([
                    'order_number'       => strtoupper(Str::random(12)),
                    'user_id'            => $user->id,
                    'trading_pair_id'    => $pair->id,
                    'type'               => 'limit',
                    'side'               => 'sell',
                    'price'              => $price,
                    'quantity'           => $qty,
                    'remaining_quantity' => $qty,
                    'total_amount'       => $price * $qty,
                    'status'             => 'pending'
                ]);
            }

        }
    }
}
