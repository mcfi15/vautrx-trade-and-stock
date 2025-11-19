<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use App\Models\GiftCardTransaction;
use Illuminate\Http\Request;

class GiftCardController extends Controller
{
    public function index()
    {
        $giftCards = GiftCard::with(['user', 'cryptocurrency', 'redeemedBy'])
            ->latest()
            ->paginate(25);

        $stats = [
            'total' => GiftCard::count(),
            'active' => GiftCard::active()->count(),
            'redeemed' => GiftCard::redeemed()->count(),
            'expired' => GiftCard::expired()->count(),
            'total_amount' => GiftCard::sum('amount'),
        ];

        return view('admin.giftcards.index', compact('giftCards', 'stats'));
    }

    public function transactions()
    {
        $transactions = GiftCardTransaction::with(['giftCard', 'user'])
            ->latest()
            ->paginate(25);

        return view('admin.giftcards.transactions', compact('transactions'));
    }

    public function destroy(GiftCard $giftcard)
    {
        if ($giftcard->status === 'redeemed') {
            return back()->with('error', 'Cannot delete a redeemed gift card.');
        }

        $giftcard->delete();

        return back()->with('success', 'Gift card deleted successfully.');
    }
}