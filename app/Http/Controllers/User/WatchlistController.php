<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Watchlist::with('stock')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.watchlist.index', compact('watchlists'));
    }

    public function update(Request $request, Watchlist $watchlist)
    {
        if ($watchlist->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'target_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $watchlist->update($request->only(['target_price', 'notes']));

        return back()->with('success', 'Watchlist item updated successfully.');
    }

    public function destroy(Watchlist $watchlist)
    {
        if ($watchlist->user_id !== Auth::id()) {
            abort(403);
        }

        $watchlist->delete();

        return back()->with('success', 'Stock removed from watchlist.');
    }
}