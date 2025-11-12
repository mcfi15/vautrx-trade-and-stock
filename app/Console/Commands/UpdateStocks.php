<?php

namespace App\Console\Commands;

use App\Services\StockDataService;
use Illuminate\Console\Command;

class UpdateStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:update 
                          {--symbol= : Update specific stock symbol}
                          {--limit= : Limit number of stocks to update}
                          {--force : Force update even if recently updated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stock data from external APIs';

    protected $stockDataService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StockDataService $stockDataService)
    {
        parent::__construct();
        $this->stockDataService = $stockDataService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting stock data update...');

        // Check if updating specific symbol
        if ($symbol = $this->option('symbol')) {
            return $this->updateSingleStock($symbol);
        }

        // Update all stocks
        return $this->updateAllStocks();
    }

    /**
     * Update a single stock
     */
    private function updateSingleStock($symbol)
    {
        $this->info("Updating {$symbol}...");

        $result = $this->stockDataService->importStock($symbol);

        if ($result['success']) {
            $this->info("✓ {$symbol} updated successfully");
            return 0;
        } else {
            $this->error("✗ Failed to update {$symbol}: " . $result['message']);
            return 1;
        }
    }

    /**
     * Update all stocks
     */
    private function updateAllStocks()
    {
        $limit = $this->option('limit');
        $force = $this->option('force');

        if ($limit) {
            $this->info("Updating up to {$limit} stocks...");
        } else {
            $this->info("Updating all stocks...");
        }

        // Start progress bar
        $stocks = \App\Models\Stock::where('is_active', true);

        if ($limit) {
            $stocks = $stocks->limit($limit);
        }

        if (!$force) {
            // Only update stocks that haven't been updated in the last hour
            $stocks = $stocks->where(function($query) {
                $query->whereNull('last_updated')
                      ->orWhere('last_updated', '<', now()->subHour());
            });
        }

        $stocks = $stocks->get();

        if ($stocks->isEmpty()) {
            $this->info('No stocks need updating.');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($stocks->count());
        $progressBar->start();

        $successful = 0;
        $failed = 0;
        $errors = [];

        foreach ($stocks as $stock) {
            $result = $this->stockDataService->importStock($stock->symbol);

            if ($result['success']) {
                $successful++;
            } else {
                $failed++;
                $errors[$stock->symbol] = $result['message'];
            }

            $progressBar->advance();

            // Small delay to respect API rate limits
            usleep(200000); // 0.2 seconds
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("Update completed:");
        $this->info("✓ Successful: {$successful}");
        
        if ($failed > 0) {
            $this->error("✗ Failed: {$failed}");
            
            if ($this->output->isVerbose()) {
                $this->newLine();
                $this->error("Failed symbols:");
                foreach ($errors as $symbol => $error) {
                    $this->error("  {$symbol}: {$error}");
                }
            }
        }

        return $failed > 0 ? 1 : 0;
    }
}
