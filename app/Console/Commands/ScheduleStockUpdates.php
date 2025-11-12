<?php

namespace App\Console\Commands;

use App\Services\StockDataService;
use App\Models\Stock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleStockUpdates extends Command
{
    protected $signature = 'stocks:scheduled-update 
                           {--market-hours-only : Only update during market hours (9:30-16:00 ET)}
                           {--max-stocks=50 : Maximum number of stocks to update per run}';

    protected $description = 'Scheduled task to update stock prices (designed for cron jobs)';

    protected $stockDataService;

    public function __construct(StockDataService $stockDataService)
    {
        parent::__construct();
        $this->stockDataService = $stockDataService;
    }

    public function handle()
    {
        $maxStocks = $this->option('max-stocks');
        $marketHoursOnly = $this->option('market-hours-only');
        
        // Check market hours if requested
        if ($marketHoursOnly && !$this->isMarketHours()) {
            Log::info('Stock update skipped: Outside market hours');
            $this->info('Market is closed. Update skipped.');
            return 0;
        }

        Log::info("Starting scheduled stock price update (max: {$maxStocks} stocks)");
        $this->info("🔄 Starting scheduled stock price update...");

        // Get stocks that need updating (older than 5 minutes or never updated)
        $stocks = Stock::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('last_updated')
                      ->orWhere('last_updated', '<', now()->subMinutes(5));
            })
            ->orderBy('last_updated', 'asc')
            ->limit($maxStocks)
            ->get();

        if ($stocks->isEmpty()) {
            $this->info('✅ All stocks are up to date');
            Log::info('All stocks are up to date');
            return 0;
        }

        $this->info("📊 Updating {$stocks->count()} stocks...");
        Log::info("Updating {$stocks->count()} stocks");

        $updated = 0;
        $failed = 0;
        $errors = [];

        foreach ($stocks as $stock) {
            try {
                $result = $this->stockDataService->importStock($stock->symbol);
                
                if ($result['success']) {
                    $updated++;
                    $this->line("✅ {$stock->symbol}: \${$stock->fresh()->current_price}");
                } else {
                    $failed++;
                    $errors[] = "{$stock->symbol}: {$result['message']}";
                    $this->line("❌ {$stock->symbol}: Failed");
                }
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "{$stock->symbol}: {$e->getMessage()}";
                $this->error("❌ {$stock->symbol}: Exception");
                Log::error("Stock update error for {$stock->symbol}: " . $e->getMessage());
            }

            // Respect API rate limits
            if ($stocks->count() > 1) {
                usleep(500000); // 0.5 seconds delay
            }
        }

        // Summary
        $this->newLine();
        $this->info("📈 Update completed:");
        $this->info("  ✅ Updated: {$updated}");
        
        if ($failed > 0) {
            $this->warn("  ❌ Failed: {$failed}");
            
            // Log errors for debugging
            foreach ($errors as $error) {
                Log::warning("Stock update error: {$error}");
            }
        }

        $successRate = $stocks->count() > 0 ? round(($updated / $stocks->count()) * 100, 1) : 0;
        $this->info("  📊 Success rate: {$successRate}%");
        
        Log::info("Scheduled stock update completed: {$updated} updated, {$failed} failed");

        return $failed > 0 ? 1 : 0;
    }

    /**
     * Check if market is currently open (approximate US market hours)
     */
    private function isMarketHours(): bool
    {
        $now = now('America/New_York');
        $dayOfWeek = $now->dayOfWeek;
        
        // Skip weekends
        if ($dayOfWeek === 0 || $dayOfWeek === 6) { // Sunday or Saturday
            return false;
        }
        
        $hour = $now->hour;
        $minute = $now->minute;
        $timeMinutes = ($hour * 60) + $minute;
        
        // Market hours: 9:30 AM - 4:00 PM ET
        $marketOpen = (9 * 60) + 30;  // 9:30 AM
        $marketClose = 16 * 60;       // 4:00 PM
        
        return $timeMinutes >= $marketOpen && $timeMinutes < $marketClose;
    }
}