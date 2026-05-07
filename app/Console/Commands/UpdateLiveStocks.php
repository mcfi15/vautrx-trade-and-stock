<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;
use App\Services\StockDataService;

class UpdateLiveStocks extends Command
{
    protected $signature = 'stocks:update-live';
    protected $description = 'Fetch real-time stock updates';

    protected $stockService;

    public function __construct(StockDataService $stockService)
    {
        parent::__construct();
        $this->stockService = $stockService;
    }

public function handle()
{
    // Get only active stocks
    $stocks = Stock::where('is_active', true)->get();
    
    $this->info("Updating " . $stocks->count() . " stocks...");

    foreach ($stocks as $stock) {
        $success = $this->stockService->updateStockFromFMP($stock);
        
        if ($success) {
            $this->line("<info>✔</info> {$stock->symbol} updated.");
        } else {
            $this->line("<fg=red>✘</fg=red> {$stock->symbol} failed.");
        }

        // Delay to respect FMP Free Tier (up to 10 calls per minute)
        // If on a paid tier, you can remove this sleep.
        sleep(6); 
    }

    $this->info("All stocks processed.");
}
}
