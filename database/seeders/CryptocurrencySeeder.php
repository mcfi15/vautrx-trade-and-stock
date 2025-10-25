<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CryptocurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cryptocurrencies = [
            [
                'symbol' => 'BTC',
                'name' => 'Bitcoin',
                'coingecko_id' => 'bitcoin',
                'binance_symbol' => 'BTCUSDT',
                'contract_address' => null,
                'blockchain' => 'Bitcoin',
                'decimals' => 8,
                'logo_url' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png',
                'current_price' => 43500.00,
                'high_24h' => 44500.00,
                'low_24h' => 42000.00,
                'price_change_24h' => 2.5,
                'market_cap' => 850000000000,
                'volume_24h' => 25000000000,
                'is_active' => true,
                'is_tradable' => true,
                'enable_realtime' => true,
                'price_updated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'symbol' => 'ETH',
                'name' => 'Ethereum',
                'coingecko_id' => 'ethereum',
                'binance_symbol' => 'ETHUSDT',
                'contract_address' => null,
                'blockchain' => 'Ethereum',
                'decimals' => 18,
                'logo_url' => 'https://assets.coingecko.com/coins/images/279/large/ethereum.png',
                'current_price' => 2650.00,
                'high_24h' => 2750.00,
                'low_24h' => 2580.00,
                'price_change_24h' => 3.2,
                'market_cap' => 320000000000,
                'volume_24h' => 12000000000,
                'is_active' => true,
                'is_tradable' => true,
                'enable_realtime' => true,
                'price_updated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'symbol' => 'BNB',
                'name' => 'Binance Coin',
                'coingecko_id' => 'binancecoin',
                'binance_symbol' => 'BNBUSDT',
                'contract_address' => null,
                'blockchain' => 'Binance Smart Chain',
                'decimals' => 18,
                'logo_url' => 'https://assets.coingecko.com/coins/images/825/large/bnb-icon2_2x.png',
                'current_price' => 315.50,
                'high_24h' => 320.00,
                'low_24h' => 310.00,
                'price_change_24h' => 1.8,
                'market_cap' => 48000000000,
                'volume_24h' => 1800000000,
                'is_active' => true,
                'is_tradable' => true,
                'enable_realtime' => true,
                'price_updated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'symbol' => 'ADA',
                'name' => 'Cardano',
                'coingecko_id' => 'cardano',
                'binance_symbol' => 'ADAUSDT',
                'contract_address' => null,
                'blockchain' => 'Cardano',
                'decimals' => 6,
                'logo_url' => 'https://assets.coingecko.com/coins/images/975/large/cardano.png',
                'current_price' => 0.45,
                'high_24h' => 0.48,
                'low_24h' => 0.42,
                'price_change_24h' => 5.1,
                'market_cap' => 15000000000,
                'volume_24h' => 400000000,
                'is_active' => true,
                'is_tradable' => true,
                'enable_realtime' => true,
                'price_updated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'symbol' => 'SOL',
                'name' => 'Solana',
                'coingecko_id' => 'solana',
                'binance_symbol' => 'SOLUSDT',
                'contract_address' => null,
                'blockchain' => 'Solana',
                'decimals' => 9,
                'logo_url' => 'https://assets.coingecko.com/coins/images/4128/large/solana.png',
                'current_price' => 98.75,
                'high_24h' => 102.00,
                'low_24h' => 95.50,
                'price_change_24h' => -1.2,
                'market_cap' => 42000000000,
                'volume_24h' => 2200000000,
                'is_active' => true,
                'is_tradable' => true,
                'enable_realtime' => true,
                'price_updated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'symbol' => 'USDT',
                'name' => 'Tether',
                'coingecko_id' => 'tether',
                'binance_symbol' => 'USDT',
                'contract_address' => null,
                'blockchain' => 'Ethereum',
                'decimals' => 6,
                'logo_url' => 'https://assets.coingecko.com/coins/images/325/large/Tether-logo.png',
                'current_price' => 1.00,
                'high_24h' => 1.01,
                'low_24h' => 0.99,
                'price_change_24h' => 0.1,
                'market_cap' => 83000000000,
                'volume_24h' => 35000000000,
                'is_active' => true,
                'is_tradable' => true,
                'enable_realtime' => true,
                'price_updated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($cryptocurrencies as $crypto) {
            DB::table('cryptocurrencies')->updateOrInsert(
                ['symbol' => $crypto['symbol']],
                $crypto
            );
        }
    }
}