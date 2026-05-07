<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
    \App\Console\Commands\UpdateStockPrices::class,
];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // 1. CRYPTO UPDATES
        $schedule->command('crypto:sync-prices')
                 ->everyMinute()
                 ->withoutOverlapping();

        $schedule->command('stocks:update-prices')->everyFiveMinutes();

        // 2. LIVE STOCK UPDATES (Price only)
        // We use withoutOverlapping to ensure if an update takes > 60s, a new one doesn't start
        $schedule->command('stocks:update-live')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();

        // 3. FULL STOCK SYNC (Metadata/Profiles)
        // Runs after market close to refresh company details without slowing down live price updates
        $schedule->command('stocks:update --force')
                 ->dailyAt('18:00')
                 ->timezone('America/New_York') // Force EST timezone
                 ->withoutOverlapping();

        // 4. MAINTENANCE TASKS
        $schedule->command('mining:process-rewards')
                 ->dailyAt('00:00');

        $schedule->command('stocks:cleanup')
                 ->weeklyOn(0, '02:00') // Sunday at 2 AM
                 ->withoutOverlapping();

                 
    }

    

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}