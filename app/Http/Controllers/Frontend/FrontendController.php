<?php

namespace App\Http\Controllers\Frontend;

use App\Models\TradingPair;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{
    public function index(){
        $tradingPairs = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->active()
            ->get();
        return view('frontend.index', compact('tradingPairs'));
    }

    public function markets(){
        $tradingPairs = TradingPair::with(['baseCurrency', 'quoteCurrency'])
            ->active()
            ->get();
        return view('frontend.markets', compact('tradingPairs'));
    }

    public function about(){
        
        return view('frontend.about');
    }
}
