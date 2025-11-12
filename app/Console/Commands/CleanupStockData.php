<?php

namespace App\Console\Commands;

use App\Models\StockPriceHistory;
use Illuminate\Console\Command;

class CleanupStockData extends Command
{
    protected $signature = 'stocks:cleanup {--days=365 : Number of days to keep}';
    protected $description = 'Clean up old stock price history data';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);
        
        $this->info("Cleaning up stock price history older than {$days} days...");
        
        $deleted = StockPriceHistory::where('date', '<', $cutoffDate)->delete();
        
        $this->info("Deleted {$deleted} old price history records.");
        
        return 0;
    }
}