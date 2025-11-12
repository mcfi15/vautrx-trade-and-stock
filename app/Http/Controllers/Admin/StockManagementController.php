<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StockDataService;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockManagementController extends Controller
{
    protected $stockDataService;

    public function __construct(StockDataService $stockDataService)
    {
        $this->stockDataService = $stockDataService;
    }

    /**
     * Display the stock management dashboard
     */
    public function index()
    {
        $stocks = Stock::where('is_active', true)
            ->orderBy('last_updated', 'desc')
            ->paginate(20);

        $stats = [
            'total_stocks' => Stock::where('is_active', true)->count(),
            'recently_updated' => Stock::where('is_active', true)
                ->where('last_updated', '>', now()->subMinutes(10))
                ->count(),
            'stale_data' => Stock::where('is_active', true)
                ->where('last_updated', '<', now()->subHour())
                ->count(),
            'total_value' => Stock::where('is_active', true)->sum('market_cap')
        ];

        return view('admin.stocks.index', compact('stocks', 'stats'));
    }

    /**
     * Import a single stock
     */
    public function importStock(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string|max:10|alpha'
        ]);

        $symbol = strtoupper($request->symbol);

        try {
            $result = $this->stockDataService->importStock($symbol);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'stock' => $result['stock'],
                    'data_source' => $result['data_source']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error("Stock import error for {$symbol}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk import stocks from predefined lists
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'list_type' => 'required|string|in:sp500,nasdaq100,dow30,popular',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $listType = $request->list_type;
        $limit = $request->limit;

        try {
            // Run bulk import in background to avoid timeout
            $results = $this->stockDataService->bulkImportStocks($listType, $limit);

            return response()->json([
                'success' => true,
                'message' => "Bulk import completed: {$results['successful']} successful, {$results['failed']} failed",
                'results' => $results
            ]);
        } catch (\Exception $e) {
            Log::error("Bulk import error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bulk import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update all stock prices
     */
    public function updatePrices(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $limit = $request->limit ?? 50;

        try {
            // Get stocks that need updating
            $stocks = Stock::where('is_active', true)
                ->where(function($query) {
                    $query->whereNull('last_updated')
                          ->orWhere('last_updated', '<', now()->subMinutes(5));
                })
                ->limit($limit)
                ->get();

            if ($stocks->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'All stocks are up to date',
                    'updated' => 0,
                    'failed' => 0
                ]);
            }

            $updated = 0;
            $failed = 0;
            $errors = [];

            foreach ($stocks as $stock) {
                try {
                    $result = $this->stockDataService->importStock($stock->symbol);
                    
                    if ($result['success']) {
                        $updated++;
                    } else {
                        $failed++;
                        $errors[] = "{$stock->symbol}: {$result['message']}";
                    }
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "{$stock->symbol}: {$e->getMessage()}";
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Price update completed: {$updated} updated, {$failed} failed",
                'updated' => $updated,
                'failed' => $failed,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error("Price update error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Price update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available stock lists
     */
    public function getStockLists()
    {
        $lists = $this->stockDataService->getPopularStockLists();
        
        return response()->json([
            'success' => true,
            'lists' => $lists
        ]);
    }

    /**
     * Get stock details via AJAX
     */
    public function getStockDetails($symbol)
    {
        $stock = Stock::where('symbol', strtoupper($symbol))->first();
        
        if (!$stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'stock' => [
                'symbol' => $stock->symbol,
                'name' => $stock->name,
                'current_price' => $stock->current_price,
                'opening_price' => $stock->opening_price,
                'closing_price' => $stock->closing_price,
                'high_price' => $stock->high_price,
                'low_price' => $stock->low_price,
                'volume' => number_format($stock->volume),
                'market_cap' => number_format($stock->market_cap),
                'change' => $stock->change,
                'change_percentage' => round($stock->change_percentage, 2),
                'sector' => $stock->sector,
                'exchange' => $stock->exchange,
                'last_updated' => $stock->last_updated->format('M j, Y g:i A'),
                'last_updated_human' => $stock->last_updated->diffForHumans()
            ]
        ]);
    }

    /**
     * Toggle stock active status
     */
    public function toggleStatus(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $stock->is_active = !$stock->is_active;
        $stock->save();

        return response()->json([
            'success' => true,
            'message' => "Stock {$stock->symbol} " . ($stock->is_active ? 'activated' : 'deactivated'),
            'is_active' => $stock->is_active
        ]);
    }

    /**
     * Delete a stock
     */
    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $symbol = $stock->symbol;
        
        // Check if stock is used in portfolios
        if ($stock->portfolios()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete {$symbol}: Stock is used in portfolios"
            ], 400);
        }

        $stock->delete();

        return response()->json([
            'success' => true,
            'message' => "Stock {$symbol} deleted successfully"
        ]);
    }
}