<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Faucet;
use App\Models\Wallet;
use App\Models\FaucetLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\FaucetClaimedMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FaucetController extends Controller
{
    // Show list (uses your provided blade)
    public function index()
    {
        $now = Carbon::now();

        // Processing: active, started, not ended
        $processing = Faucet::where('is_active', [1, '1', true, 'true'])
            ->where('start_at', '<=', $now)
            ->where(function($q) use ($now) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
            })
            ->orderBy('start_at','asc')
            ->get();

        // Upcoming: active, start in future
        $upcoming = Faucet::where('is_active', [1, '1', true, 'true'])
            ->where('start_at', '>', $now)
            ->orderBy('start_at','asc')
            ->get();

        // Ended: has ended
        $ended = Faucet::whereNotNull('end_at')
            ->where('end_at','<',$now)
            ->orderBy('end_at','desc')
            ->get();

        return view('faucet.index', compact('processing','upcoming','ended'));
    }

    // Claim faucet (user must be authenticated)
    public function claim(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error','Please login to claim.');
        }

        $faucet = Faucet::findOrFail($id);

        // basic checks
        if (!$faucet->is_active || ($faucet->start_at && $faucet->start_at->gt(now())) || ($faucet->end_at && $faucet->end_at->lt(now()))) {
            return redirect()->back()->with('error','Faucet not active.');
        }

        // count previous claims
        $totalClaims = FaucetLog::where('faucet_id',$faucet->id)->where('user_id',$user->id)->count();
        if ($faucet->max_claims_per_user > 0 && $totalClaims >= $faucet->max_claims_per_user) {
            return redirect()->back()->with('error','You have reached the maximum number of claims for this faucet.');
        }

        // cooldown: find last claim
        $last = FaucetLog::where('faucet_id',$faucet->id)->where('user_id',$user->id)->latest('claimed_at')->first();
        if ($last) {
            $nextAllowed = $last->claimed_at->addSeconds($faucet->cooldown_seconds);
            if ($nextAllowed->isFuture()) {
                $diff = $nextAllowed->diffForHumans(now(), \Carbon\Carbon::DIFF_ABSOLUTE);
                return redirect()->back()->with('error','Please wait '.$diff.' before claiming again.');
            }
        }

        // perform credit - wrap in DB transaction
        DB::beginTransaction();
        try {
            // ensure faucet amount positive
            if (bccomp((string)$faucet->amount, '0', 8) <= 0) {
                throw new \Exception('Invalid faucet amount.');
            }

            // find or create wallet for coin
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id, 'cryptocurrency_id' => $faucet->cryptocurrency_id],
                ['address' => null, 'balance' => 0, 'locked_balance' => 0]
            );

            // before balances for transaction record
            $balanceBefore = (string)$wallet->balance;

            // credit wallet
            $wallet->balance = bcadd((string)$wallet->balance, (string)$faucet->amount, 18);
            $wallet->save();

            // create transaction
            Transaction::create([
                'user_id' => $user->id,
                'cryptocurrency_id' => $faucet->cryptocurrency_id,
                'type' => 'faucet',
                'amount' => $faucet->amount,
                'fee' => 0,
                'balance_before' => $balanceBefore,
                'balance_after' => (string)$wallet->balance,
                'description' => "Faucet claim: {$faucet->title}",
                'status' => 'completed',
            ]);

            // create faucet log
            FaucetLog::create([
                'faucet_id' => $faucet->id,
                'user_id' => $user->id,
                'amount' => $faucet->amount,
                'cryptocurrency_id' => $faucet->cryptocurrency_id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'reason' => null,
                'claimed_at' => now(),
            ]);

            DB::commit();

            Mail::to($user->email)->send(new FaucetClaimedMail($user, $faucet, $faucet->amount));

            return redirect()->back()->with('success','Faucet claimed successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();
            // record failed log
            FaucetLog::create([
                'faucet_id' => $faucet->id,
                'user_id' => $user->id,
                'amount' => $faucet->amount,
                'cryptocurrency_id' => $faucet->cryptocurrency_id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'failed',
                'reason' => $e->getMessage(),
                'claimed_at' => now(),
            ]);
            return redirect()->back()->with('error','Failed to claim faucet: '.$e->getMessage());
        }
    }
}
