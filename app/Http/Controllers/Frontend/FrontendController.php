<?php

namespace App\Http\Controllers\Frontend;

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
}
