<?php

namespace App\Console\Commands;

use App\Models\UserMiningMachine;
use App\Models\MiningReward;
use App\Models\Wallet;
use App\Mail\MiningRewardReceived;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProcessMiningRewards extends Command
{
    protected $signature = 'mining:process-rewards';
    protected $description = 'Process daily mining rewards for active mining machines';

    public function handle()
    {
        $activeMachines = UserMiningMachine::with(['user', 'miningPool'])
            ->where('is_active', true)
            ->where('end_date', '>', now())
            ->get();

        $processed = 0;

        foreach ($activeMachines as $machine) {
            DB::transaction(function () use ($machine, &$processed) {
                // Create reward record
                $reward = MiningReward::create([
                    'user_id' => $machine->user_id,
                    'mining_pool_id' => $machine->mining_pool_id,
                    'user_mining_machine_id' => $machine->id,
                    'amount' => $machine->daily_reward,
                    'reward_date' => now()->toDateString(),
                    'is_paid' => false,
                ]);

                // Auto-claim if setting is enabled
                if (config('mining.auto_claim_rewards', false)) {
                    $ltcWallet = $machine->user->getWalletBySymbol('LTC');
                    if ($ltcWallet) {
                        $ltcWallet->addBalance($machine->daily_reward);
                        $reward->update([
                            'is_paid' => true,
                            'paid_at' => now()
                        ]);

                        // Send email notification
                        Mail::to($machine->user->email)->send(new MiningRewardReceived($reward));
                    }
                }

                $processed++;
            });
        }

        $this->info("Processed rewards for {$processed} mining machines.");
        
        // Deactivate expired machines
        $expired = UserMiningMachine::where('is_active', true)
            ->where('end_date', '<=', now())
            ->update(['is_active' => false, 'status' => 'completed']);

        $this->info("Deactivated {$expired} expired mining machines.");
    }
}