<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CoinGeckoService;
use Illuminate\Support\Facades\Log;

class SyncCryptoPrices extends Command
{
    protected $signature = 'crypto:sync-prices';
    protected $description = 'Sync cryptocurrency prices from CoinGecko';

    public function handle()
    {
        $this->info('Starting crypto price sync...');

        try {
            app(CoinGeckoService::class)->updateAllPrices();
            $this->info('Prices updated successfully.');
            Log::info('crypto:sync-prices: success');
            return 0;
        } catch (\Throwable $e) {
            $this->error('Failed to update prices: ' . $e->getMessage());
            Log::error('crypto:sync-prices error: '.$e->getMessage());
            return 1;
        }
    }
}
