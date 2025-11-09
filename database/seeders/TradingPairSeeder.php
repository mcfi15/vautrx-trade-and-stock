<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TradingPair;
use App\Models\Cryptocurrency;

class TradingPairSeeder extends Seeder
{
    public function run()
    {
        $quoteCurrencies = ['USDT', 'BTC', 'ETH', 'EUR'];

        $cryptos = Cryptocurrency::where('is_active', 1)->get();

        foreach ($quoteCurrencies as $quoteSymbol) {
            $quoteCoin = $cryptos->firstWhere('symbol', $quoteSymbol);
            if (!$quoteCoin) continue;

            foreach ($cryptos as $baseCoin) {
                if ($baseCoin->id === $quoteCoin->id) continue;

                // Random realistic values for seed data
                $lastPrice = rand(100, 60000) / 100; // e.g. 123.45
                $high24h = $lastPrice * (1 + rand(1, 10) / 100);
                $low24h  = $lastPrice * (1 - rand(1, 10) / 100);
                $changePercent = rand(-1000, 1000) / 100; // -10% to +10%
                $volume24h = rand(1000, 500000) / 10; // e.g. 50k volume

                TradingPair::updateOrCreate(
                    [
                        'base_currency_id' => $baseCoin->id,
                        'quote_currency_id' => $quoteCoin->id
                    ],
                    [
                        'symbol' => $baseCoin->symbol . '/' . $quoteCoin->symbol,
                        'min_trade_amount' => 0.0001,
                        'max_trade_amount' => 100000,
                        'price_precision' => 6,
                        'quantity_precision' => 6,
                        'trading_fee' => 0.1,
                        'is_active' => 1,

                        // ✅ new market data fields
                        'last_price' => $lastPrice,
                        'price_change_percent' => $changePercent,
                        'high_24h' => $high24h,
                        'low_24h' => $low24h,
                        'volume_24h' => $volume24h,
                    ]
                );
            }
        }

        echo "✅ Trading pairs generated with market data (last price, change %, high/low, volume)\n";
    }
}
