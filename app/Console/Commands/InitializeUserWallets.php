<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class InitializeUserWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallets:initialize {--user-id= : Specific user ID to initialize}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize wallets for existing users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');

        if ($userId) {
            $this->initializeWalletsForUser($userId);
        } else {
            $this->initializeWalletsForAllUsers();
        }

        return Command::SUCCESS;
    }

    private function initializeWalletsForAllUsers()
    {
        $users = User::all();
        $count = 0;

        $this->info('Initializing wallets for all users...');
        $this->output->progressStart($users->count());

        foreach ($users as $user) {
            if ($user->wallets()->count() === 0) {
                $user->createDefaultWallets();
                $count++;
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info("Created wallets for {$count} users.");
    }

    private function initializeWalletsForUser($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        $existingWallets = $user->wallets()->count();
        $user->createDefaultWallets();
        $newWallets = $user->wallets()->count();

        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Existing wallets: {$existingWallets}");
        $this->info("Total wallets: {$newWallets}");
        $this->info("New wallets created: " . ($newWallets - $existingWallets));
    }
}