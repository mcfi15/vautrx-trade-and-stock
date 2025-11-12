<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Services\StockDataService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $stockDataService;

    public function __construct(StockDataService $stockDataService)
    {
        $this->stockDataService = $stockDataService;
    }

    public function index(Request $request)
    {
        try {
            $query = Stock::query();

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('symbol', 'like', "%{$search}%");
                });
            }

            if ($request->has('sector') && !empty($request->get('sector'))) {
                $query->where('sector', $request->get('sector'));
            }

            if ($request->has('exchange') && !empty($request->get('exchange'))) {
                $query->where('exchange', $request->get('exchange'));
            }

            $stocks = $query->withCount(['transactions', 'portfolios'])
                           ->orderBy('name', 'asc')
                           ->paginate(10);

            // Get distinct sectors and exchanges, filtering out null/empty values
            $sectors = Stock::distinct()
                           ->whereNotNull('sector')
                           ->where('sector', '!=', '')
                           ->pluck('sector')
                           ->sort()
                           ->values();
                           
            $exchanges = Stock::distinct()
                             ->whereNotNull('exchange')
                             ->where('exchange', '!=', '')
                             ->pluck('exchange')
                             ->sort()
                             ->values();

            // Ensure we always have collections, even if empty
            $sectors = $sectors ?: collect();
            $exchanges = $exchanges ?: collect();

            return view('admin.stocks.index', compact('stocks', 'sectors', 'exchanges'));
            
        } catch (\Exception $e) {
            // Log the error and provide fallback
            \Log::error('Error in StockController@index: ' . $e->getMessage());
            
            // Fallback with empty collections
            $stocks = Stock::latest()->paginate(15);
            $sectors = collect();
            $exchanges = collect();
            
            return view('admin.stocks.index', compact('stocks', 'sectors', 'exchanges'))
                   ->with('error', 'There was an issue loading filter options. Please try again.');
        }
    }

    public function create()
    {
        return view('admin.stocks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string|unique:stocks|max:10',
            'name' => 'required|string|max:255',
            'current_price' => 'required|numeric|min:0',
            'sector' => 'nullable|string|max:100',
            'exchange' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['last_updated'] = now();

        Stock::create($data);

        return redirect()->route('admin.stocks.index')
                        ->with('success', 'Stock created successfully.');
    }

    public function show(Stock $stock)
    {
        $stock->load(['transactions.user', 'portfolios.user']);
        
        return view('admin.stocks.show', compact('stock'));
    }

    public function edit(Stock $stock)
    {
        return view('admin.stocks.edit', compact('stock'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'symbol' => 'required|string|max:10|unique:stocks,symbol,' . $stock->id,
            'name' => 'required|string|max:255',
            'current_price' => 'required|numeric|min:0',
            'sector' => 'nullable|string|max:100',
            'exchange' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['last_updated'] = now();

        $stock->update($data);

        return redirect()->route('admin.stocks.index')
                        ->with('success', 'Stock updated successfully.');
    }

    public function destroy(Stock $stock)
    {
        if ($stock->transactions()->exists() || $stock->portfolios()->exists()) {
            return back()->with('error', 'Cannot delete stock with existing transactions or portfolios.');
        }

        $stock->delete();

        return redirect()->route('admin.stocks.index')
                        ->with('success', 'Stock deleted successfully.');
    }

    public function toggleStatus(Stock $stock)
    {
        $stock->update(['is_active' => !$stock->is_active]);
        
        return back()->with('success', 'Stock status updated successfully.');
    }

    /**
     * Show automatic import page
     */
    public function autoImport()
    {
        $stockLists = $this->stockDataService->getPopularStockLists();
        $totalStocks = Stock::count();
        $activeStocks = Stock::where('is_active', true)->count();
        
        // Get the last update as a proper Carbon instance
        $lastUpdateRaw = Stock::max('last_updated');
        $lastUpdate = $lastUpdateRaw ? \Carbon\Carbon::parse($lastUpdateRaw) : null;
        
        // Add stocks, sectors and exchanges to prevent undefined variable errors
        // in case any shared components or partials need them
        $stocks = Stock::latest('last_updated')->paginate(15);
        
        $sectors = Stock::distinct()
                       ->whereNotNull('sector')
                       ->where('sector', '!=', '')
                       ->pluck('sector')
                       ->sort()
                       ->values();
                       
        $exchanges = Stock::distinct()
                         ->whereNotNull('exchange')
                         ->where('exchange', '!=', '')
                         ->pluck('exchange')
                         ->sort()
                         ->values();

        // Ensure we always have collections, even if empty
        $sectors = $sectors ?: collect();
        $exchanges = $exchanges ?: collect();

        return view('admin.stocks.auto-import', compact(
            'stockLists', 
            'totalStocks', 
            'activeStocks', 
            'lastUpdate', 
            'stocks',
            'sectors', 
            'exchanges'
        ));
    }

    /**
     * Import single stock by symbol
     */
    public function importStock(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string|max:10'
        ]);

        $symbol = strtoupper(trim($request->symbol));
        $result = $this->stockDataService->importStock($symbol);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Bulk import stocks from predefined lists
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'list_type' => 'required|string|in:sp500,nasdaq100,dow30,popular',
            'limit' => 'nullable|integer|min:1|max:500'
        ]);

        $listType = $request->list_type;
        $limit = $request->limit;

        // Start the import process (this might take a while)
        set_time_limit(300); // 5 minutes

        $results = $this->stockDataService->bulkImportStocks($listType, $limit);

        $message = "Import completed: {$results['successful']} successful, {$results['failed']} failed out of {$results['total']} stocks.";
        
        if ($results['failed'] > 0) {
            $errorSummary = implode(', ', array_keys($results['errors']));
            $message .= " Failed symbols: " . $errorSummary;
        }

        return back()->with('success', $message);
    }

    /**
     * Update all existing stocks with fresh data
     */
    public function updateAllStocks()
    {
        // This might take a while for many stocks
        set_time_limit(600); // 10 minutes

        $results = $this->stockDataService->updateAllStocks();

        $message = "Update completed: {$results['successful']} successful, {$results['failed']} failed out of {$results['total']} stocks.";
        
        if ($results['failed'] > 0) {
            $errorSummary = implode(', ', array_keys($results['errors']));
            $message .= " Failed symbols: " . $errorSummary;
        }

        return back()->with('success', $message);
    }

    /**
     * Sync single stock with latest data
     */
    public function syncStock(Stock $stock)
    {
        $result = $this->stockDataService->importStock($stock->symbol);

        if ($result['success']) {
            return back()->with('success', "Stock {$stock->symbol} synced successfully.");
        } else {
            return back()->with('error', "Failed to sync {$stock->symbol}: " . $result['message']);
        }
    }

    /**
     * Add demo stocks for testing purposes
     */
    public function addDemoStocks()
    {
        try {
            $demoStocks = [
                [
                    'symbol' => 'AAPL',
                    'name' => 'Apple Inc.',
                    'current_price' => 150.00,
                    'opening_price' => 148.50,
                    'closing_price' => 149.20,
                    'high_price' => 152.00,
                    'low_price' => 147.80,
                    'volume' => 75000000,
                    'market_cap' => 2500000000000,
                    'sector' => 'Technology',
                    'exchange' => 'NASDAQ',
                    'is_active' => true,
                    'last_updated' => now(),
                ],
                [
                    'symbol' => 'TSLA',
                    'name' => 'Tesla, Inc.',
                    'current_price' => 250.00,
                    'opening_price' => 248.50,
                    'closing_price' => 245.20,
                    'high_price' => 255.00,
                    'low_price' => 243.80,
                    'volume' => 45000000,
                    'market_cap' => 800000000000,
                    'sector' => 'Consumer Cyclical',
                    'exchange' => 'NASDAQ',
                    'is_active' => true,
                    'last_updated' => now(),
                ],
                [
                    'symbol' => 'MSFT',
                    'name' => 'Microsoft Corporation',
                    'current_price' => 330.00,
                    'opening_price' => 328.50,
                    'closing_price' => 329.20,
                    'high_price' => 332.00,
                    'low_price' => 327.80,
                    'volume' => 35000000,
                    'market_cap' => 2450000000000,
                    'sector' => 'Technology',
                    'exchange' => 'NASDAQ',
                    'is_active' => true,
                    'last_updated' => now(),
                ]
            ];

            $added = 0;
            foreach ($demoStocks as $stockData) {
                $existing = Stock::where('symbol', $stockData['symbol'])->first();
                if (!$existing) {
                    Stock::create($stockData);
                    $added++;
                }
            }

            if ($added > 0) {
                return back()->with('success', "Added {$added} demo stocks successfully.");
            } else {
                return back()->with('info', 'Demo stocks already exist in the database.');
            }

        } catch (\Exception $e) {
            \Log::error('Error adding demo stocks: ' . $e->getMessage());
            return back()->with('error', 'Failed to add demo stocks: ' . $e->getMessage());
        }
    }
}