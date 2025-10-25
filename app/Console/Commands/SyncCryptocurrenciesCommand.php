<?php

namespace App\Console\Commands;

use App\Models\Cryptocurrency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncCryptocurrenciesCommand extends Command
{
    protected $signature = 'crypto:sync {--limit=50 : Number of top cryptocurrencies to sync}';
    protected $description = 'Sync cryptocurrency data from Binance';

    public function handle()
    {
        $this->info('🔄 Syncing cryptocurrencies from Binance...');
        
        try {
            // Get all trading pairs from Binance
            $response = Http::get('https://api.binance.com/api/v3/ticker/24hr');
            
            if (!$response->successful()) {
                $this->error('❌ Failed to fetch data from Binance');
                return 1;
            }

            $tickers = $response->json();
            $limit = (int) $this->option('limit');
            $syncCount = 0;

            // Filter USDT pairs and sort by volume
            $usdtPairs = collect($tickers)
                ->filter(fn($ticker) => str_ends_with($ticker['symbol'], 'USDT'))
                ->sortByDesc('quoteVolume')
                ->take($limit);

            $this->info("📊 Processing top {$limit} cryptocurrencies by volume...");
            $progressBar = $this->output->createProgressBar($usdtPairs->count());

            foreach ($usdtPairs as $ticker) {
                $symbol = str_replace('USDT', '', $ticker['symbol']);
                
                // Skip stablecoins and wrapped tokens
                if (in_array($symbol, ['USDT', 'USDC', 'BUSD', 'DAI', 'TUSD'])) {
                    continue;
                }

                $crypto = Cryptocurrency::updateOrCreate(
                    ['symbol' => $symbol],
                    [
                        'name' => $this->getCryptoName($symbol),
                        'binance_symbol' => $ticker['symbol'],
                        'coingecko_id' => strtolower($symbol),
                        'current_price' => $ticker['lastPrice'],
                        'price_change_24h' => $ticker['priceChangePercent'],
                        'volume_24h' => $ticker['volume'],
                        'high_24h' => $ticker['highPrice'],
                        'low_24h' => $ticker['lowPrice'],
                        'is_active' => true,
                        'is_tradable' => true,
                        'price_updated_at' => now(),
                    ]
                );

                $syncCount++;
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);
            $this->info("✅ Successfully synced {$syncCount} cryptocurrencies");
            $this->newLine();
            $this->info('💡 Next steps:');
            $this->info('1. Go to Admin Panel -> Cryptocurrencies');
            $this->info('2. Enable "Real-time Tracking" for coins you want to monitor');
            $this->info('3. Run: php artisan crypto:websocket start');
            
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }

    private function getCryptoName($symbol)
    {
        $names = [
            'BTC' => 'Bitcoin',
            'ETH' => 'Ethereum',
            'BNB' => 'Binance Coin',
            'XRP' => 'Ripple',
            'ADA' => 'Cardano',
            'DOGE' => 'Dogecoin',
            'SOL' => 'Solana',
            'DOT' => 'Polkadot',
            'MATIC' => 'Polygon',
            'AVAX' => 'Avalanche',
            'LINK' => 'Chainlink',
            'UNI' => 'Uniswap',
            'ATOM' => 'Cosmos',
            'LTC' => 'Litecoin',
            'ETC' => 'Ethereum Classic',
        ];

        return $names[$symbol] ?? ucfirst(strtolower($symbol));
    }
}
