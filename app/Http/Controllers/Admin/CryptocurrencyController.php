<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Services\CoinGeckoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CryptocurrencyController extends Controller
{
    private $coinGeckoService;

    public function __construct(CoinGeckoService $coinGeckoService)
    {
        // Middleware applied at route level (auth:admin)
        $this->coinGeckoService = $coinGeckoService;
    }

    public function index()
    {
        $cryptocurrencies = Cryptocurrency::latest()->paginate(20);
        return view('admin.cryptocurrencies.index', compact('cryptocurrencies'));
    }

    public function show($id)
    {
        $cryptocurrency = Cryptocurrency::findOrFail($id);
        return view('admin.cryptocurrencies.show', compact('cryptocurrency'));
    }

    public function create()
    {
        return view('admin.cryptocurrencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string|max:10|unique:cryptocurrencies',
            'name' => 'required|string|max:255',
            'coingecko_id' => 'required|string|unique:cryptocurrencies',
            'contract_address' => 'nullable|string',
            'blockchain' => 'required|in:ethereum,bsc,polygon',
            'decimals' => 'required|integer|min:0|max:18',
        ]);

        try {
            // Get initial data from CoinGecko
            $priceData = $this->coinGeckoService->getPrice($request->coingecko_id);
            $coinDetails = $this->coinGeckoService->getCoinDetails($request->coingecko_id);

            $cryptocurrency = Cryptocurrency::create([
                'symbol' => strtoupper($request->symbol),
                'name' => $request->name,
                'coingecko_id' => $request->coingecko_id,
                'contract_address' => $request->contract_address,
                'blockchain' => $request->blockchain,
                'decimals' => $request->decimals,
                'logo_url' => $coinDetails['image']['large'] ?? null,
                'current_price' => $priceData['usd'] ?? 0,
                'price_change_24h' => $priceData['usd_24h_change'] ?? 0,
                'market_cap' => $priceData['usd_market_cap'] ?? 0,
                'volume_24h' => $priceData['usd_24h_vol'] ?? 0,
                'is_active' => true,
                'is_tradable' => true,
                'price_updated_at' => now(),
            ]);

            return redirect()->route('admin.cryptocurrencies.index')
                ->with('success', 'Cryptocurrency added successfully');
        } catch (\Exception $e) {
            Log::error('Add Cryptocurrency Error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to add cryptocurrency: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $cryptocurrency = Cryptocurrency::findOrFail($id);
        return view('admin.cryptocurrencies.edit', compact('cryptocurrency'));
    }

    public function update(Request $request, $id)
    {
        $cryptocurrency = Cryptocurrency::findOrFail($id);

        $request->validate([
            'symbol' => 'required|string|max:10|unique:cryptocurrencies,symbol,' . $id,
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'is_tradable' => 'boolean',
            'enable_realtime' => 'boolean',
        ]);

        $cryptocurrency->update($request->only([
            'symbol',
            'name',
            'is_active',
            'is_tradable',
            'enable_realtime',
        ]));

        return redirect()->route('admin.cryptocurrencies.index')
            ->with('success', 'Cryptocurrency updated successfully');
    }

    public function toggleRealtime($id)
    {
        $cryptocurrency = Cryptocurrency::findOrFail($id);
        $cryptocurrency->enable_realtime = !$cryptocurrency->enable_realtime;
        $cryptocurrency->save();

        $status = $cryptocurrency->enable_realtime ? 'enabled' : 'disabled';
        
        return response()->json([
            'success' => true,
            'message' => "Real-time tracking {$status} for {$cryptocurrency->symbol}",
            'enable_realtime' => $cryptocurrency->enable_realtime,
        ]);
    }

    /**
     * Toggle cryptocurrency active status.
     */
    public function toggleStatus($id)
    {
        $cryptocurrency = Cryptocurrency::findOrFail($id);
        $cryptocurrency->is_active = !$cryptocurrency->is_active;
        $cryptocurrency->save();

        $status = $cryptocurrency->is_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true,
            'message' => "Cryptocurrency {$status} successfully",
            'is_active' => $cryptocurrency->is_active,
        ]);
    }

    /**
     * Sync prices from external APIs.
     */
    public function syncPrices()
    {
        try {
            $cryptocurrencies = Cryptocurrency::active()->get();
            $updated = 0;
            $failed = 0;

            foreach ($cryptocurrencies as $crypto) {
                try {
                    $priceData = $this->coinGeckoService->getPrice($crypto->coingecko_id);
                    
                    if ($priceData) {
                        $crypto->update([
                            'current_price' => $priceData['usd'] ?? $crypto->current_price,
                            'price_change_24h' => $priceData['usd_24h_change'] ?? $crypto->price_change_24h,
                            'market_cap' => $priceData['usd_market_cap'] ?? $crypto->market_cap,
                            'volume_24h' => $priceData['usd_24h_vol'] ?? $crypto->volume_24h,
                            'price_updated_at' => now(),
                        ]);
                        $updated++;
                    } else {
                        $failed++;
                    }
                } catch (\Exception $e) {
                    $failed++;
                    Log::warning("Failed to update price for {$crypto->symbol}", ['error' => $e->getMessage()]);
                }
            }

            $message = "Price sync completed: {$updated} updated";
            if ($failed > 0) {
                $message .= ", {$failed} failed";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'updated' => $updated,
                'failed' => $failed,
                'total' => $cryptocurrencies->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Price Sync Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync prices: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $cryptocurrency = Cryptocurrency::findOrFail($id);
        $cryptocurrency->delete();

        return redirect()->route('admin.cryptocurrencies.index')
            ->with('success', 'Cryptocurrency deleted successfully');
    }

    public function updatePrices()
    {
        try {
            $cryptocurrencies = Cryptocurrency::active()->get();
            $updated = 0;

            foreach ($cryptocurrencies as $crypto) {
                $priceData = $this->coinGeckoService->getPrice($crypto->coingecko_id);
                
                if ($priceData) {
                    $crypto->update([
                        'current_price' => $priceData['usd'] ?? $crypto->current_price,
                        'price_change_24h' => $priceData['usd_24h_change'] ?? $crypto->price_change_24h,
                        'market_cap' => $priceData['usd_market_cap'] ?? $crypto->market_cap,
                        'volume_24h' => $priceData['usd_24h_vol'] ?? $crypto->volume_24h,
                        'price_updated_at' => now(),
                    ]);
                    $updated++;
                }
            }

            return redirect()->back()->with('success', "Updated prices for {$updated} cryptocurrencies");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update prices: ' . $e->getMessage());
        }
    }

    public function syncFromBinance(Request $request)
    {
        try {
            $limit = $request->input('limit', 50);
            
            // Run the sync command
            \Illuminate\Support\Facades\Artisan::call('crypto:sync', [
                '--limit' => $limit
            ]);

            $output = \Illuminate\Support\Facades\Artisan::output();
            
            // Count how many were synced
            $count = Cryptocurrency::count();

            return response()->json([
                'success' => true,
                'message' => "Successfully synced {$count} cryptocurrencies from Binance",
                'count' => $count,
            ]);

        } catch (\Exception $e) {
            Log::error('Sync from Binance Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync: ' . $e->getMessage(),
            ], 500);
        }
    }
}
