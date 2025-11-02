<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cryptocurrency;
use App\Services\CoinGeckoService;

class UpdateCryptoPrices extends Command
{
    protected $signature = 'crypto:update-prices';
    protected $description = 'Update cryptocurrency prices from CoinGecko';

    private $coinGeckoService;

    public function __construct(CoinGeckoService $coinGeckoService)
    {
        parent::__construct();
        $this->coinGeckoService = $coinGeckoService;
    }

    public function handle()
    {
        $this->info('Updating cryptocurrency prices...');

        $cryptocurrencies = Cryptocurrency::active()->get();
        $updated = 0;

        foreach ($cryptocurrencies as $crypto) {
            $this->info("Updating {$crypto->symbol}...");
            
            $priceData = $this->coinGeckoService->getPrice($crypto->coingecko_id);
            
            if ($priceData) {
                $crypto->update([
                    'current_price' => $priceData['usd'] ?? $crypto->current_price,
                    'price_change_24h' => $priceData['usd_24h_change'] ?? $crypto->price_change_24h,
                    'market_cap' => $priceData['usd_market_cap'] ?? $crypto->market_cap,
                    'volume_24h' => $priceData['usd_24h_vol'] ?? $crypto->volume_24h,
                    'price_updated_at' => now(),
                ]);
                $updated++;
                $this->info("Updated {$crypto->symbol}: \${$priceData['usd']}");
            } else {
                $this->error("Failed to update {$crypto->symbol}");
            }
        }

        // Mark that price update service has run
        \Illuminate\Support\Facades\Cache::put('last_price_update', now(), 3600);

        $this->info("Updated {$updated} cryptocurrencies successfully!");
    }
}
