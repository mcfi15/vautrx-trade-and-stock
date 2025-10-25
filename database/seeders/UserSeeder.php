<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TradingPair;
use App\Models\Cryptocurrency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create cryptocurrencies
        $usdt = Cryptocurrency::create([
            'symbol' => 'USDT',
            'name' => 'Tether',
            'coingecko_id' => 'tether',
            'contract_address' => '0xdac17f958d2ee523a2206206994597c13d831ec7',
            'blockchain' => 'ethereum',
            'decimals' => 6,
            'current_price' => 1.00,
            'price_change_24h' => 0.00,
            'is_active' => true,
            'is_tradable' => true,
        ]);

        $btc = Cryptocurrency::create([
            'symbol' => 'BTC',
            'name' => 'Bitcoin',
            'coingecko_id' => 'bitcoin',
            'blockchain' => 'ethereum',
            'decimals' => 8,
            'current_price' => 45000.00,
            'price_change_24h' => 2.5,
            'market_cap' => 850000000000,
            'volume_24h' => 35000000000,
            'is_active' => true,
            'is_tradable' => true,
        ]);

        $eth = Cryptocurrency::create([
            'symbol' => 'ETH',
            'name' => 'Ethereum',
            'coingecko_id' => 'ethereum',
            'blockchain' => 'ethereum',
            'decimals' => 18,
            'current_price' => 2500.00,
            'price_change_24h' => 3.2,
            'market_cap' => 300000000000,
            'volume_24h' => 15000000000,
            'is_active' => true,
            'is_tradable' => true,
        ]);

        $bnb = Cryptocurrency::create([
            'symbol' => 'BNB',
            'name' => 'Binance Coin',
            'coingecko_id' => 'binancecoin',
            'blockchain' => 'bsc',
            'decimals' => 18,
            'current_price' => 320.00,
            'price_change_24h' => 1.8,
            'market_cap' => 50000000000,
            'volume_24h' => 2000000000,
            'is_active' => true,
            'is_tradable' => true,
        ]);

        // Create trading pairs
        TradingPair::create([
            'base_currency_id' => $btc->id,
            'quote_currency_id' => $usdt->id,
            'symbol' => 'BTC/USDT',
            'min_trade_amount' => 10,
            'max_trade_amount' => 1000000,
            'price_precision' => 0.01,
            'quantity_precision' => 0.00000001,
            'trading_fee' => 0.001,
            'is_active' => true,
        ]);

        TradingPair::create([
            'base_currency_id' => $eth->id,
            'quote_currency_id' => $usdt->id,
            'symbol' => 'ETH/USDT',
            'min_trade_amount' => 10,
            'max_trade_amount' => 1000000,
            'price_precision' => 0.01,
            'quantity_precision' => 0.00000001,
            'trading_fee' => 0.001,
            'is_active' => true,
        ]);

        TradingPair::create([
            'base_currency_id' => $bnb->id,
            'quote_currency_id' => $usdt->id,
            'symbol' => 'BNB/USDT',
            'min_trade_amount' => 10,
            'max_trade_amount' => 1000000,
            'price_precision' => 0.01,
            'quantity_precision' => 0.00000001,
            'trading_fee' => 0.001,
            'is_active' => true,
        ]);

        // Create initial wallet for test user with USDT balance
        $testUser = User::where('email', 'user@test.com')->first();
        $testUser->wallets()->create([
            'cryptocurrency_id' => $usdt->id,
            'balance' => 10000,
            'locked_balance' => 0,
        ]);
    }
}
