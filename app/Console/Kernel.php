<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process commission payments daily at 9:00 AM
        $schedule->command('commission:process-payments')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Process network bonuses daily at 10:00 AM
        $schedule->command('bonus:process-network')
            ->dailyAt('10:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Process daily profit sharing daily at 11:00 AM
        $schedule->command('profit:process-daily-sharing')
            ->dailyAt('11:00')
            ->withoutOverlapping()
            ->runInBackground();
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