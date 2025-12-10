<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\UserExam;

class PendingExamReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $userExam;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, UserExam $userExam)
    {
        $this->user = $user;
        $this->userExam = $userExam;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⏰ User Exam Reminder - Pending Assignment')
            ->greeting('Hello Admin,')
            ->line('A user has not completed their assigned exam within the scheduled deadline.')
            ->line('**User Details:**')
            ->line('- Name: ' . $this->user->name)
            ->line('- Email: ' . $this->user->email)
            ->line('**Exam Details:**')
            ->line('- Exam: ' . $this->userExam->exam->title)
            ->line('- Assigned Date: ' . $this->userExam->created_at->format('d M Y H:i'))
            ->line('- Deadline: ' . $this->userExam->scheduled_deadline->format('d M Y H:i'))
            ->line('- Days Overdue: ' . now()->diffInDays($this->userExam->scheduled_deadline))
            ->action('View User Exams', route('admin.user-exams.index'))
            ->line('Please follow up with the user to ensure they complete the exam.')
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'pending_exam_reminder',
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'exam_id' => $this->userExam->exam->id,
            'exam_title' => $this->userExam->exam->title,
            'user_exam_id' => $this->userExam->id,
            'scheduled_deadline' => $this->userExam->scheduled_deadline,
            'days_overdue' => now()->diffInDays($this->userExam->scheduled_deadline),
            'message' => "User {$this->user->name} has not completed exam '{$this->userExam->exam->title}' by the deadline.",
        ];
    }
}
