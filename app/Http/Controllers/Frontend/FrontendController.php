<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Stock;
use App\Models\TradingPair;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;
use App\Http\Controllers\Controller;

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

	return view('frontend.index', compact('markets'));
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
