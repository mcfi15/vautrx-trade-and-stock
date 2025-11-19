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
        $stocks = Stock::where('is_active', true)->get();

        foreach ($stocks as $stock) {
            $this->stockService->updateSingleStock($stock->symbol);
        }

        $this->info("Live stock update complete.");
    }
}
