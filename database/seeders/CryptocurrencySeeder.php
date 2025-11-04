<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CryptocurrencySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $cryptos = [
            ['symbol' => 'BTC', 'name' => 'Bitcoin', 'coingecko_id' => 'bitcoin', 'blockchain' => 'Bitcoin', 'decimals' => 8, 'logo_url' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png'],
            ['symbol' => 'ETH', 'name' => 'Ethereum', 'coingecko_id' => 'ethereum', 'blockchain' => 'Ethereum', 'decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/279/large/ethereum.png'],
            ['symbol' => 'USDT', 'name' => 'Tether', 'coingecko_id' => 'tether', 'blockchain' => 'Multi-chain', 'decimals' => 6, 'logo_url' => 'https://assets.coingecko.com/coins/images/325/large/Tether-logo.png'],
            ['symbol' => 'BNB', 'name' => 'BNB', 'coingecko_id' => 'binancecoin', 'blockchain' => 'BNB Chain', 'decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/825/large/bnb.png'],
            ['symbol' => 'SOL', 'name' => 'Solana', 'coingecko_id' => 'solana', 'blockchain' => 'Solana', 'decimals' => 9, 'logo_url' => 'https://assets.coingecko.com/coins/images/4128/large/solana.png'],
            ['symbol' => 'XRP', 'name' => 'XRP', 'coingecko_id' => 'ripple', 'blockchain' => 'XRP Ledger', 'decimals' => 6, 'logo_url' => 'https://assets.coingecko.com/coins/images/44/large/xrp.png'],
            ['symbol' => 'ADA', 'name' => 'Cardano', 'coingecko_id' => 'cardano', 'blockchain' => 'Cardano', 'decimals' => 6, 'logo_url' => 'https://assets.coingecko.com/coins/images/975/large/cardano.png'],
            ['symbol' => 'DOGE', 'name' => 'Dogecoin', 'coingecko_id' => 'dogecoin', 'blockchain' => 'Dogecoin', 'decimals' => 8, 'logo_url' => 'https://assets.coingecko.com/coins/images/5/large/dogecoin.png'],
            ['symbol' => 'TRX', 'name' => 'Tron', 'coingecko_id' => 'tron', 'blockchain' => 'Tron', 'decimals' => 6, 'logo_url' => 'https://assets.coingecko.com/coins/images/1094/large/tron-logo.png'],
            ['symbol' => 'DOT', 'name' => 'Polkadot', 'coingecko_id' => 'polkadot', 'blockchain' => 'Polkadot', 'decimals' => 10, 'logo_url' => 'https://assets.coingecko.com/coins/images/12171/large/polkadot.png'],
            ['symbol' => 'MATIC', 'name' => 'Polygon', 'coingecko_id' => 'matic-network', 'blockchain' => 'Polygon', 'decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/4713/large/matic-token-icon.png'],
            ['symbol' => 'LTC', 'name' => 'Litecoin', 'coingecko_id' => 'litecoin', 'blockchain' => 'Litecoin', 'decimals' => 8, 'logo_url' => 'https://assets.coingecko.com/coins/images/2/large/litecoin.png'],
            ['symbol' => 'LINK', 'name' => 'Chainlink', 'coingecko_id' => 'chainlink', 'blockchain' => 'Ethereum', 'decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/877/large/chainlink-new-logo.png'],
            ['symbol' => 'BCH', 'name' => 'Bitcoin Cash', 'coingecko_id' => 'bitcoin-cash', 'blockchain' => 'Bitcoin Cash', 'decimals' => 8, 'logo_url' => 'https://assets.coingecko.com/coins/images/780/large/bitcoin-cash-circle.png'],
            ['symbol' => 'AVAX', 'name' => 'Avalanche', 'coingecko_id' => 'avalanche-2', 'blockchain' => 'Avalanche', 'decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/12559/large/avax.png'],
            ['symbol' => 'SHIB', 'name' => 'Shiba Inu', 'coingecko_id' => 'shiba-inu', 'blockchain' => 'Ethereum', 'decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/11939/large/shiba.png'],

            // ✅ 50 new coins
            ['symbol' => 'PEPE', 'name' => 'Pepe', 'coingecko_id' => 'pepe', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/29850/large/pepe-token.jpeg'],
            ['symbol' => 'BONK', 'name' => 'Bonk', 'coingecko_id' => 'bonk', 'blockchain' => 'Solana','decimals' => 5, 'logo_url' => 'https://assets.coingecko.com/coins/images/28600/large/bonk.jpg'],
            ['symbol' => 'TAO', 'name' => 'Bittensor', 'coingecko_id' => 'bittensor', 'blockchain' => 'Own Chain','decimals' => 9, 'logo_url' => 'https://assets.coingecko.com/coins/images/24906/large/bit_tensor.jpeg'],
            ['symbol' => 'INJ', 'name' => 'Injective', 'coingecko_id' => 'injective-protocol', 'blockchain' => 'Cosmos','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/12882/large/Secondary_Symbol.png'],
            ['symbol' => 'TIA', 'name' => 'Celestia', 'coingecko_id' => 'celestia', 'blockchain' => 'Modular','decimals' => 6, 'logo_url' => 'https://assets.coingecko.com/coins/images/33410/large/tia.jpeg'],
            ['symbol' => 'APE', 'name' => 'ApeCoin', 'coingecko_id' => 'apecoin', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/24383/large/apecoin.jpg'],
            ['symbol' => 'DYDX', 'name' => 'dYdX', 'coingecko_id' => 'dydx', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/17500/large/hjnIm9bV.jpg'],
            ['symbol' => 'RPL', 'name' => 'Rocket Pool', 'coingecko_id' => 'rocket-pool', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/2090/large/rocket_pool.png'],
            ['symbol' => 'GMX', 'name' => 'GMX', 'coingecko_id' => 'gmx', 'blockchain' => 'Arbitrum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/18323/large/arbit.png'],
            ['symbol' => 'RNDR', 'name' => 'Render', 'coingecko_id' => 'render-token', 'blockchain' => 'Solana','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/11636/large/rndr.png'],
            ['symbol' => 'CORE', 'name' => 'Core', 'coingecko_id' => 'coredaoorg', 'blockchain' => 'Core','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/28545/large/core.png'],
            ['symbol' => 'CFX', 'name' => 'Conflux', 'coingecko_id' => 'conflux-token', 'blockchain' => 'Conflux','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/14047/large/Conflux.png'],
            ['symbol' => 'AXS', 'name' => 'Axie Infinity', 'coingecko_id' => 'axie-infinity', 'blockchain' => 'Ronin','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/13029/large/axie.png'],
            ['symbol' => 'SXP', 'name' => 'SXP', 'coingecko_id' => 'swipe', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/9368/large/SXP.png'],
            ['symbol' => 'SUI', 'name' => 'Sui', 'coingecko_id' => 'sui', 'blockchain' => 'Sui','decimals' => 
            9, 'logo_url' => 'https://assets.coingecko.com/coins/images/25765/large/sui_logo.png'],
            ['symbol' => 'AR', 'name' => 'Arweave', 'coingecko_id' => 'arweave', 'blockchain' => 'Arweave','decimals' => 
            18, 'logo_url' => 'https://assets.coingecko.com/coins/images/4343/large/Arweave.png'],
            ['symbol' => 'WOO', 'name' => 'WOO Network', 'coingecko_id' => 'woo-network', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/19117/large/WOO.png'],
            ['symbol' => 'FET', 'name' => 'Fetch.ai', 'coingecko_id' => 'fetch-ai', 'blockchain' => 'Cosmos','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/5681/large/Fetch.jpg'],
            ['symbol' => 'AKT', 'name' => 'Akash Network', 'coingecko_id' => 'akash-network', 'blockchain' => 'Cosmos','decimals' => 6, 'logo_url' => 'https://assets.coingecko.com/coins/images/12783/large/akash.png'],
            ['symbol' => 'ENJ', 'name' => 'Enjin Coin', 'coingecko_id' => 'enjincoin', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/1102/large/enjin-coin-logo.png'],
            ['symbol' => 'JASMY', 'name' => 'JasmyCoin', 'coingecko_id' => 'jasmycoin', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/13876/large/JASMY.jpg'],
            ['symbol' => 'ZIL', 'name' => 'Zilliqa', 'coingecko_id' => 'zilliqa', 'blockchain' => 'Zilliqa','decimals' => 12, 'logo_url' => 'https://assets.coingecko.com/coins/images/2687/large/Zilliqa-logo.png'],
            ['symbol' => 'GNO', 'name' => 'Gnosis', 'coingecko_id' => 'gnosis', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/662/large/gnosis.png'],
            ['symbol' => 'ANKR', 'name' => 'Ankr', 'coingecko_id' => 'ankr', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/4324/large/ankr.png'],
            ['symbol' => 'ENS', 'name' => 'Ethereum Name Service', 'coingecko_id' => 'ethereum-name-service', 'blockchain' => 'Ethereum','decimals' => 18, 'logo_url' => 'https://assets.coingecko.com/coins/images/19785/large/ens.png'],
        ];

        foreach ($cryptos as $crypto) {
            // Prevent duplicate records
            if (!DB::table('cryptocurrencies')->where('coingecko_id', $crypto['coingecko_id'])->exists()) {
                DB::table('cryptocurrencies')->insert(array_merge($crypto, [
                    'current_price' => 0,
                    'price_change_24h' => 0,
                    'market_cap' => 0,
                    'volume_24h' => 0,
                    'is_active' => 1,
                    'is_tradable' => 1,
                    'price_updated_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }
}
