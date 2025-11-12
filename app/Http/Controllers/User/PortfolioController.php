<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $portfolios = Portfolio::with('stock')
        ->where('user_id', $user->id)
        ->get();

    // Calculate totals
    $totalValue = $portfolios->sum(function ($p) {
        return $p->stock->current_price * $p->quantity;
    });

    $totalInvestment = $portfolios->sum(function ($p) {
        return $p->buy_price * $p->quantity;
    });

    $profitLoss = $totalValue - $totalInvestment;

    $profitLossPercentage = $totalInvestment > 0 
        ? ($profitLoss / $totalInvestment) * 100
        : 0;

    $stats = [
        'total_value' => $totalValue,
        'total_investment' => $totalInvestment,
        'profit_loss' => $profitLoss,
        'profit_loss_percentage' => $profitLossPercentage,
    ];

    return view('user.portfolio.index', compact('portfolios', 'stats'));
}


    public function show(Portfolio $portfolio)
    {
        if ($portfolio->user_id !== Auth::id()) {
            abort(403);
        }
        
        $portfolio->load('stock');
        
        return view('user.portfolio.show', compact('portfolio'));
    }
}