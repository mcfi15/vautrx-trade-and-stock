<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\GiftCard;
use App\Models\GiftCardTransaction;
use App\Models\Cryptocurrency;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Mail\GiftCardCreated;
use App\Mail\GiftCardRedeemed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class GiftCardController extends Controller
{
    public function index()
    {
        $myCards = GiftCard::with('cryptocurrency')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        $spentCards = GiftCard::with('cryptocurrency')
            ->where('redeemed_by', Auth::id())
            ->latest()
            ->get();

        return view('user.giftcard.index', compact('myCards', 'spentCards'));
    }

    public function create()
    {
        $cryptocurrencies = Cryptocurrency::active()->get();
        return view('user.giftcard.create', compact('cryptocurrencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'amount' => 'required|numeric|min:0.00000001',
            'title' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000',
            'expires_at' => 'nullable|date|after:today'
        ]);

        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $crypto = Cryptocurrency::findOrFail($request->cryptocurrency_id);
            $amount = $request->amount;

            // Get user's wallet for the selected cryptocurrency
            $wallet = $user->getWallet($crypto->id);
            if (!$wallet || $wallet->available_balance < $amount) {
                return back()->with('error', "Insufficient {$crypto->symbol} balance.");
            }

            // Deduct balance from wallet
            if (!$wallet->subtractBalance($amount)) {
                return back()->with('error', 'Failed to process gift card creation.');
            }

            // Create gift card
            $giftCard = GiftCard::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $crypto->id,
                'title' => $request->title,
                'amount' => $amount,
                'message' => $request->message,
                'expires_at' => $request->expires_at,
            ]);

            // Create gift card transaction
            GiftCardTransaction::create([
                'gift_card_id' => $giftCard->id,
                'user_id' => $user->id,
                'type' => 'creation',
                'amount' => -$amount,
                'description' => "Created gift card: {$giftCard->public_code}"
            ]);

            // Create main transaction record
            Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $crypto->id,
                'type' => 'gift_card_creation',
                'amount' => $amount,
                'fee' => 0,
                'balance_before' => $wallet->balance + $amount,
                'balance_after' => $wallet->balance,
                'status' => 'completed',
                'description' => "Created gift card: {$giftCard->public_code}"
            ]);

            // Send email notification
            Mail::to($user->email)->send(new GiftCardCreated($giftCard));

            return redirect()->route('giftcard.index')->with('success', 'Gift card created successfully!');
        });
    }

    public function checkValue(Request $request)
    {
        $request->validate([
            'secret_code' => 'required|string'
        ]);

        $giftCard = GiftCard::with(['cryptocurrency', 'user'])
            ->where('secret_code', $request->secret_code)
            ->first();

        if (!$giftCard) {
            return response()->json([
                'status' => 0,
                'info' => 'Gift card not found.'
            ]);
        }

        if ($giftCard->status === 'redeemed') {
            return response()->json([
                'status' => 0,
                'info' => 'This gift card has already been redeemed.'
            ]);
        }

        if ($giftCard->isExpired()) {
            return response()->json([
                'status' => 0,
                'info' => 'This gift card has expired.'
            ]);
        }

        return response()->json([
            'status' => 1,
            'info' => "Gift Card: {$giftCard->title}\nValue: {$giftCard->amount} {$giftCard->cryptocurrency->symbol}\nStatus: Active"
        ]);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'secret_code' => 'required|string'
        ]);

        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $giftCard = GiftCard::with('cryptocurrency')
                ->where('secret_code', $request->secret_code)
                ->first();

            if (!$giftCard) {
                return response()->json([
                    'status' => 0,
                    'info' => 'Gift card not found.'
                ]);
            }

            if ($giftCard->status === 'redeemed') {
                return response()->json([
                    'status' => 0,
                    'info' => 'This gift card has already been redeemed.'
                ]);
            }

            if ($giftCard->isExpired()) {
                return response()->json([
                    'status' => 0,
                    'info' => 'This gift card has expired.'
                ]);
            }

            if ($giftCard->user_id === $user->id) {
                return response()->json([
                    'status' => 0,
                    'info' => 'You cannot redeem your own gift card.'
                ]);
            }

            // Get user's wallet for the cryptocurrency
            $wallet = $user->getWallet($giftCard->cryptocurrency_id);
            if (!$wallet) {
                $wallet = $user->getOrCreateWallet($giftCard->cryptocurrency_id);
            }

            // Add balance to wallet
            $wallet->addBalance($giftCard->amount);

            // Update gift card status
            $giftCard->update([
                'status' => 'redeemed',
                'redeemed_by' => $user->id,
                'redeemed_at' => now()
            ]);

            // Create gift card transaction
            GiftCardTransaction::create([
                'gift_card_id' => $giftCard->id,
                'user_id' => $user->id,
                'type' => 'redemption',
                'amount' => $giftCard->amount,
                'description' => "Redeemed gift card: {$giftCard->public_code}"
            ]);

            // Create main transaction record
            Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $giftCard->cryptocurrency_id,
                'type' => 'gift_card_redemption',
                'amount' => $giftCard->amount,
                'fee' => 0,
                'balance_before' => $wallet->balance - $giftCard->amount,
                'balance_after' => $wallet->balance,
                'status' => 'completed',
                'description' => "Redeemed gift card: {$giftCard->public_code}"
            ]);

            // Send email notifications
            Mail::to($user->email)->send(new GiftCardRedeemed($giftCard, 'redeemer'));
            Mail::to($giftCard->user->email)->send(new GiftCardRedeemed($giftCard, 'creator'));

            return response()->json([
                'status' => 1,
                'info' => 'Gift card redeemed successfully!'
            ]);
        });
    }

    public function viewCode($id)
    {
        $giftCard = GiftCard::with('cryptocurrency')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'title' => $giftCard->title,
            'coin' => $giftCard->cryptocurrency->symbol,
            'value' => $giftCard->amount,
            'public' => $giftCard->public_code,
            'secret' => $giftCard->secret_code
        ]);
    }

    public function viewConsumed($id)
    {
        $giftCard = GiftCard::with('cryptocurrency')
            ->where('redeemed_by', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'title' => $giftCard->title,
            'coin' => $giftCard->cryptocurrency->symbol,
            'value' => $giftCard->amount,
            'public' => $giftCard->public_code,
            'used' => $giftCard->redeemed_at->format('Y-m-d H:i:s')
        ]);
    }
}
