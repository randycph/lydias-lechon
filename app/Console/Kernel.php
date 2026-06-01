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
        // $schedule->command('inspire')->hourly();
        $schedule->command('cart:check')->daily();
        $schedule->command('transactions:check-unpaid')->dailyAt('01:00');
        $schedule->command('users:delete-old-guests')->dailyAt('02:00');

        $schedule->command('sitemap:generate')->dailyAt('03:00');
        // $schedule->command('update:deliverable-cities-schedules')->everyFiveMinutes();
        $schedule->command('queue:work --stop-when-empty')->everyMinute();
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
