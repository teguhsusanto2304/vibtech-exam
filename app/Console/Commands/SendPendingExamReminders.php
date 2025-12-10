<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserExam;
use App\Models\User;
use App\Notifications\PendingExamReminder;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class SendPendingExamReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam:send-pending-reminders
                            {--days=3 : Number of days before deadline to send reminder}
                            {--days-after=1 : Number of days after deadline to send overdue reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to admin for pending user exams that are approaching or past deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysBeforeDeadline = $this->option('days');
        $daysAfterDeadline = $this->option('days-after');

        $this->info("🔍 Checking for pending exams...");
        $this->info("⏰ Days before deadline: {$daysBeforeDeadline}");
        $this->info("⏰ Days after deadline: {$daysAfterDeadline}");
        $this->newLine();

        // Get all admins
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('⚠️  No admins found in the system.');
            return 0;
        }

        // Get pending exams
        $pendingExams = UserExam::with(['user', 'exam'])
            ->where('data_status', 'pending')
            ->whereNotNull('scheduled_deadline')
            ->get();

        if ($pendingExams->isEmpty()) {
            $this->info('✅ No pending exams found.');
            return 0;
        }

        $remindersSent = 0;
        $overdueCount = 0;
        $upcomingDeadlineCount = 0;

        foreach ($pendingExams as $userExam) {
            $now = Carbon::now();
            $deadline = Carbon::parse($userExam->scheduled_deadline);
            $daysUntilDeadline = $now->diffInDays($deadline, false);

            // Check if notification already sent
            if ($userExam->notification_sent) {
                continue;
            }

            // Case 1: Deadline passed (overdue)
            if ($now->isAfter($deadline)) {
                $daysOverdue = $now->diffInDays($deadline);
                
                // Send notification only on first day overdue and every 3 days after
                if ($daysOverdue <= $daysAfterDeadline || $daysOverdue % 3 === 0) {
                    $this->sendNotificationToAdmins($admins, $userExam->user, $userExam);
                    $remindersSent++;
                    $overdueCount++;
                    
                    $this->line("📧 Overdue notification sent for: {$userExam->user->name} - {$userExam->exam->title} ({$daysOverdue} days overdue)");
                }
            }
            // Case 2: Approaching deadline
            elseif ($daysUntilDeadline <= $daysBeforeDeadline && $daysUntilDeadline > 0) {
                $this->sendNotificationToAdmins($admins, $userExam->user, $userExam);
                $remindersSent++;
                $upcomingDeadlineCount++;
                
                $this->line("📧 Approaching deadline notification sent for: {$userExam->user->name} - {$userExam->exam->title} ({$daysUntilDeadline} days left)");
            }
        }

        $this->newLine();
        $this->info("✨ Summary:");
        $this->info("📬 Total reminders sent: {$remindersSent}");
        $this->info("⏳ Upcoming deadline notifications: {$upcomingDeadlineCount}");
        $this->info("🚨 Overdue notifications: {$overdueCount}");

        return 0;
    }

    /**
     * Send notification to all admins
     */
    protected function sendNotificationToAdmins($admins, $user, $userExam)
    {
        Notification::send($admins, new PendingExamReminder($user, $userExam));
        
        // Mark notification as sent
        $userExam->notification_sent = true;
        $userExam->save();
    }
}
