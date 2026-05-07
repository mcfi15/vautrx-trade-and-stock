<?php
// app/Console/Commands/UpdateStockPrices.php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Http\Controllers\Admin\StockPriceUpdateController;
use Illuminate\Console\Command;

class UpdateStockPrices extends Command
{
    protected $signature = 'stocks:update-prices';
    protected $description = 'Update all active stock prices in real-time';

    public function handle()
    {
        $controller = app(StockPriceUpdateController::class);
        $result = $controller->bulkUpdatePrices(request());
        
        $this->info('Stock prices updated successfully');
    }
}