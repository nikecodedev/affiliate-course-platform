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
        // Process commission payments every hour
        $schedule->command('bonus:process-commissions')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();

        // Process network bonuses every 30 minutes
        $schedule->command('bonus:process-network')
            ->everyThirtyMinutes()
            ->withoutOverlapping()
            ->runInBackground();

        // Process daily profit sharing daily at 11:00 PM
        $schedule->command('bonus:process-daily-profit-sharing')
            ->dailyAt('23:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Clean up old logs weekly
        $schedule->command('log:clear')
            ->weekly()
            ->sundays()
            ->at('02:00');

        // Backup database daily at 3:00 AM
        $schedule->command('backup:run')
            ->dailyAt('03:00')
            ->withoutOverlapping();

        // Generate daily reports at midnight
        $schedule->command('reports:generate-daily')
            ->daily()
            ->at('00:05')
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