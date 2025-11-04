<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TradingPair;
use App\Models\Cryptocurrency;

class TradingPairSeeder extends Seeder
{
    public function run()
    {
        // Quote currencies we want markets for
        $quoteCurrencies = ['USDT', 'BTC', 'ETH', 'EUR'];

        // Get ALL cryptos from DB
        $cryptos = Cryptocurrency::where('is_active', 1)->get();

        foreach ($quoteCurrencies as $quoteSymbol) {

            $quoteCoin = $cryptos->firstWhere('symbol', $quoteSymbol);
            if (!$quoteCoin) continue;

            foreach ($cryptos as $baseCoin) {

                // Skip if base == quote (e.g BTC/BTC)
                if ($baseCoin->id === $quoteCoin->id) continue;

                // Create pair
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
                    ]
                );
            }
        }

        echo "✅ Trading pairs generated for USDT, BTC, ETH & EUR\n";
    }
}
