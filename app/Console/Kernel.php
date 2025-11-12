<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Run the Laravel command that syncs crypto prices every minute
        $schedule->command('crypto:sync-prices')->everyMinute()->withoutOverlapping();

        // Update stock prices every 5 minutes during market hours (9 AM - 4 PM EST)
        $schedule->command('stocks:update-prices')
                 ->everyFiveMinutes()
                 ->between('09:00', '16:00')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Full stock data update once daily at 6 PM EST (after market close)
        $schedule->command('stocks:update --force')
                 ->dailyAt('18:00')
                 ->withoutOverlapping();

        // Clean up old stock price history (older than 1 year)
        $schedule->command('stocks:cleanup')
                 ->weekly()
                 ->sundays()
                 ->at('02:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
