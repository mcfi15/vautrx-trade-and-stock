<?php

namespace App\Console\Commands;

use App\Services\StockDataService;
use Illuminate\Console\Command;

class ImportStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:import 
                          {list : Stock list type (sp500, nasdaq100, dow30, popular)}
                          {--limit= : Limit number of stocks to import}
                          {--force : Import even if stock already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import stocks from popular lists (S&P 500, NASDAQ 100, etc.)';

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
        $listType = $this->argument('list');
        $limit = $this->option('limit');

        // Validate list type
        $availableLists = $this->stockDataService->getPopularStockLists();
        
        if (!array_key_exists($listType, $availableLists)) {
            $this->error("Invalid list type: {$listType}");
            $this->info("Available lists: " . implode(', ', array_keys($availableLists)));
            return 1;
        }

        $selectedList = $availableLists[$listType];
        
        $this->info("Importing stocks from: {$selectedList['name']}");
        $this->info("Description: {$selectedList['description']}");
        
        if ($limit) {
            $this->info("Import limit: {$limit} stocks");
        }

        if (!$this->confirm('Do you want to proceed with the import?')) {
            $this->info('Import cancelled.');
            return 0;
        }

        // Start the import
        $this->info('Starting import process...');
        
        $results = $this->stockDataService->bulkImportStocks($listType, $limit);

        // Create progress bar simulation since bulk import handles the actual work
        $progressBar = $this->output->createProgressBar($results['total']);
        
        for ($i = 0; $i < $results['total']; $i++) {
            $progressBar->advance();
            usleep(50000); // Simulate progress
        }
        
        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("Import completed:");
        $this->info("✓ Total processed: {$results['total']}");
        $this->info("✓ Successful: {$results['successful']}");
        
        if ($results['failed'] > 0) {
            $this->error("✗ Failed: {$results['failed']}");
            
            if ($this->output->isVerbose()) {
                $this->newLine();
                $this->error("Failed symbols:");
                foreach ($results['errors'] as $symbol => $error) {
                    $this->error("  {$symbol}: {$error}");
                }
            } else {
                $this->info("Use -v flag to see detailed error messages");
            }
        }

        $successRate = round(($results['successful'] / $results['total']) * 100, 1);
        $this->info("Success rate: {$successRate}%");

        return $results['failed'] > 0 ? 1 : 0;
    }
}
