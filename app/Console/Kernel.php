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
        // Send pending exam reminders
        // Run daily at 9 AM - 3 days before deadline
        $schedule->command('exam:send-pending-reminders --days=3')
            ->dailyAt('09:00')
            ->description('Send reminder 3 days before exam deadline')
            ->name('pending-exam-reminders-3days');

        // Run daily at 5 PM - 1 day before deadline
        $schedule->command('exam:send-pending-reminders --days=1')
            ->dailyAt('17:00')
            ->description('Send reminder 1 day before exam deadline')
            ->name('pending-exam-reminders-1day');

        // Run daily at 10 AM - overdue exams (check every day for 3 days after)
        $schedule->command('exam:send-pending-reminders --days-after=3')
            ->dailyAt('10:00')
            ->description('Send reminders for overdue exams')
            ->name('pending-exam-reminders-overdue');

        // Optional: Cleanup old notifications (keep last 30 days)
        $schedule->command('notifications:prune')
            ->daily()
            ->description('Prune old notifications')
            ->name('prune-notifications');
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
