<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Stock;
use App\Models\Wallet;
use App\Models\TradingPair;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function index(){
        $markets = [
        'USDT' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'USDT'))
            ->active()
            ->limit(7)
            ->get(),

        'BTC' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'BTC'))
            ->active()
            ->limit(7)
            ->get(),

        'ETH' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'ETH'))
            ->active()
            ->limit(7)
            ->get(),

        'EUR' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'EUR'))
            ->active()
            ->limit(7)
            ->get(),
        ];

        $user = Auth::user();
        $cryptos = Cryptocurrency::where('is_active', true)->get();
        $balances = [];

        if ($user) {
            
            foreach ($cryptos as $crypto) {
                $balances[$crypto->symbol] = $user->getWallet($crypto->id)?->available_balance ?? 0;
            }
        }

	return view('frontend.index', compact('markets', 'cryptos', 'balances', 'user'));
    }

    public function doTrade(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['status' => 0, 'info' => 'Please login first.']);
    }

    $request->validate([
        'coin' => 'required|exists:cryptocurrencies,symbol',
        'amount' => 'required|numeric|min:0.00000001',
        'type' => 'required|in:buy'
    ]);

    $user = Auth::user();
    $coin = Cryptocurrency::where('symbol', $request->coin)->first();
    $amount = $request->amount;

    DB::beginTransaction();
    try {
        $usdtWallet = $user->getWalletBySymbol('USDT');
        if (!$usdtWallet || $usdtWallet->balance < $amount) {
            return response()->json(['status' => 0, 'info' => 'Insufficient USDT balance.']);
        }

        $coinWallet = $user->getWallet($coin->id);
        if (!$coinWallet) {
            $coinWallet = Wallet::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $coin->id,
                'balance' => 0,
                'locked_balance' => 0
            ]);
        }

        // Deduct USDT, credit crypto
        $usdtWallet->decrement('balance', $amount);
        $coinWallet->increment('balance', $amount / $coin->current_price);

        DB::commit();
        return response()->json(['status' => 1, 'info' => 'Bought '.$coin->symbol.' successfully!']);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => 0, 'info' => 'Trade failed: '.$e->getMessage()]);
    }
}

    public function markets(){
        $markets = [
        'USDT' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'USDT'))
            ->active()
            // ->limit(15)
            ->get(),

        'BTC' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'BTC'))
            ->active()
            // ->limit(15)
            ->get(),

        'ETH' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'ETH'))
            ->active()
            // ->limit(15)
            ->get(),

        'EUR' => TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->whereHas('quoteCurrency', fn($q) => $q->where('symbol', 'EUR'))
            ->active()
            // ->limit(15)
            ->get(),
        ];

        return view('frontend.markets', compact('markets'));
    }


    public function about(){
        
        return view('frontend.about');
    }

    public function article_1(){
        return view('frontend.article.1');
    }
    public function article_2(){
        return view('frontend.article.2');
    }
    public function article_3(){
        return view('frontend.article.3');
    }
    public function article_4(){
        return view('frontend.article.4');
    }
    public function article_10(){
        return view('frontend.article.10');
    }
    public function article_27(){
        return view('frontend.article.27');
    }
    public function article_11(){
        return view('frontend.article.11');
    }
    public function article_13(){
        return view('frontend.article.13');
    }
    public function article_24(){
        return view('frontend.article.24');
    }
    public function article_25(){
        return view('frontend.article.25');
    }
    public function article_26(){
        return view('frontend.article.26');
    }
    public function dex(){
        return view('frontend.dex');
    }

    public function stock()
    {
        // // Redirect authenticated users to dashboard
        // if (auth()->check()) {
        //     return redirect()->route('user.dashboard');
        // }

        // Get all active stocks ordered by market cap (largest first)
        $stocks = Stock::where('is_active', true)
                      ->orderBy('name', 'asc')
                      ->limit(20) // Show top 20 stocks on homepage
                      ->get();

        return view('frontend.stock', compact('stocks'));
    }
}
