<?php

/**
 * Get a setting value
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function getSetting($key, $default = null)
{
    return \App\Models\Setting::getValue($key, $default);
}

/**
 * Get the application name
 * 
 * @return string
 */
function appName()
{
    return getSetting('app_name', config('app.name'));
}

/**
 * Get the application logo
 * 
 * @return string
 */
function appLogo()
{
    return getSetting('app_logo', '/images/logo.png');
}

/**
 * Get the support email
 * 
 * @return string
 */
function supportEmail()
{
    return getSetting('support_email', env('MAIL_FROM_ADDRESS'));
}

/**
 * Get pending exams for a user
 * 
 * @param \App\Models\User $user
 * @return \Illuminate\Database\Eloquent\Collection
 */
function getPendingExams($user)
{
    return $user->userExams()
        ->with('exam')
        ->where('data_status', 'pending')
        ->get();
}

function getOverDueUserExams()
{
    $userExams = \App\Models\UserExam::query()
    ->where('data_status', 'pending')
        
    ->where('end_date', '<', now()) 

    ->whereNull('finished_at') 
    
    // 3. Mengambil hasil (Collection)

        ->with(['user', 'exam'])
        ->get();
    
    $admins = \App\Models\User::where('role', 'admin')->get();
    
    foreach($userExams as $row)
    {
        $userExamUpdate = \App\Models\UserExam::find($row->id);
        $userExamUpdate->data_status = 'cancel';
        $userExamUpdate->save();
        
        // Send notification to admins
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\ExamStatusUpdated($userExamUpdate, $userExamUpdate->user->name, 'cancel'));
    }
    
    return $userExams;
}

/**
 * Get overdue exams (passed deadline but not completed)
 * 
 * @return \Illuminate\Database\Eloquent\Collection
 */
function getOverdueExams()
{
    return \App\Models\UserExam::with(['user', 'exam'])
        ->where('data_status', 'pending')
        ->whereNotNull('scheduled_deadline')
        ->where('scheduled_deadline', '<', now())
        ->get();
}

/**
 * Check if exam is approaching deadline
 * 
 * @param \App\Models\UserExam $userExam
 * @param int $days
 * @return bool
 */
function isApproachingDeadline($userExam, $days = 3)
{
    if (!$userExam->scheduled_deadline) {
        return false;
    }

    $daysUntil = now()->diffInDays($userExam->scheduled_deadline, false);
    return $daysUntil <= $days && $daysUntil > 0;
}

/**
 * Check if exam is overdue
 * 
 * @param \App\Models\UserExam $userExam
 * @return bool
 */
function isExamOverdue($userExam)
{
    if (!$userExam->scheduled_deadline) {
        return false;
    }

    return now()->isAfter($userExam->scheduled_deadline) && $userExam->data_status === 'pending';
}

/**
 * Get days remaining for exam deadline
 * 
 * @param \App\Models\UserExam $userExam
 * @return int|null
 */
function daysRemaining($userExam)
{
    if (!$userExam->scheduled_deadline) {
        return null;
    }

    return now()->diffInDays($userExam->scheduled_deadline, false);
}
