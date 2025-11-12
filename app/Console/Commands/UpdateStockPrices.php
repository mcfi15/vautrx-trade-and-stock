<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Services\StockDataService;
use Illuminate\Console\Command;

class UpdateStockPrices extends Command
{
    protected $signature = 'stocks:update-prices {--all : Update all active stocks}';
    protected $description = 'Update stock prices for live market data';
    
    protected $stockDataService;

    public function __construct(StockDataService $stockDataService)
    {
        parent::__construct();
        $this->stockDataService = $stockDataService;
    }

    public function handle()
    {
        $this->info('Starting stock price updates...');
        
        // Get active stocks that need updates (older than 5 minutes)
        $stocks = Stock::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('last_updated')
                      ->orWhere('last_updated', '<', now()->subMinutes(5));
            })
            ->limit($this->option('all') ? 1000 : 50) // Limit to prevent API overuse
            ->get();
            
        if ($stocks->isEmpty()) {
            $this->info('No stocks need updating.');
            return 0;
        }
        
        $this->info("Updating {$stocks->count()} stocks...");
        
        $bar = $this->output->createProgressBar($stocks->count());
        $bar->start();
        
        $updated = 0;
        $failed = 0;
        
        foreach ($stocks as $stock) {
            $result = $this->stockDataService->importStock($stock->symbol);
            
            if ($result['success']) {
                $updated++;
            } else {
                $failed++;
            }
            
            $bar->advance();
            
            // Small delay to respect API limits
            usleep(100000); // 0.1 seconds
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Update completed: {$updated} successful, {$failed} failed");
        
        return 0;
    }
}