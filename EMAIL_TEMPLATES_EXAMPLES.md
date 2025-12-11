# Email Template Examples dengan Dynamic Settings

## 📧 Template 1: Exam Result Email (SUDAH DIUPDATE)

**File:** `resources/views/emails/exam-result.blade.php`

```blade
<x-mail::message>
@php
    $appLogo = getSetting('app_logo', '/images/logo.png');
    $appName = getSetting('app_name', config('app.name'));
    $supportEmail = getSetting('support_email', config('mail.from.address'));
@endphp

<!-- Logo Header -->
<div style="text-align: center; margin-bottom: 30px;">
    @if($appLogo && $appLogo !== '/images/logo.png')
        <img src="{{ $appLogo }}" alt="{{ $appName }} Logo" style="max-width: 200px; height: auto;">
    @else
        <div style="font-size: 24px; font-weight: bold; color: #005A9C;">{{ $appName }}</div>
    @endif
</div>

# Examination Result Notification

Hello **{{ $student->name }}**,

## Candidate Information
- **Full Name:** {{ $student->name }}
- **Company:** {{ $student->company ?? 'N/A' }}
- **Email:** {{ $student->email }}

## Examination Details
- **Exam Name:** {{ $result->exam->title }}
- **Completion Date:** {{ $result->finished_at->format("d M Y, H:i") }}
- **Attempts:** {{ $result->attempts_used }} of 3
- **Your Score:** {{ $result->scores }}%
- **Passing Score:** {{ $result->exam->pass_mark }}%

## Result Status
@if($status === 'passed')
<div style="background-color: #D1FAE5; border-left: 4px solid #10B981; padding: 15px; margin: 20px 0;">
    ✅ PASSED - Congratulations! You have successfully passed the examination.
</div>
@else
<div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 15px; margin: 20px 0;">
    ❌ FAILED - You did not pass this examination. You can attempt again if available.
</div>
@endif

---

Best regards,<br>
**{{ $appName }}**<br>
{{ $supportEmail }}
</x-mail::message>
```

---

## 📧 Template 2: Welcome Email

**File:** `resources/views/emails/welcome.blade.php`

```blade
<x-mail::message>
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
@endphp

<div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #E5E7EB; padding-bottom: 20px;">
    @if($appLogo)
        <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 150px; height: auto;">
    @else
        <h1 style="color: #005A9C; margin: 0;">{{ $appName }}</h1>
    @endif
</div>

# Welcome to {{ $appName }}!

Hello **{{ $user->name }}**,

Thank you for joining us! We're excited to have you on board.

## Account Details
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Company:** {{ $user->company ?? 'Not provided' }}
- **Registration Date:** {{ $user->created_at->format('d M Y') }}

## Getting Started
1. Complete your user profile
2. Review available examinations
3. Start taking exams to improve your skills

## Important Notes
- Keep your password secure and don't share it with anyone
- You have 3 attempts per examination
- Results will be sent to your email after completion

If you have any questions, feel free to contact our support team.

Thanks,<br>
**{{ $appName }}** Team
</x-mail::message>
```

---

## 📧 Template 3: Exam Assignment Notification

**File:** `resources/views/emails/exam-assigned.blade.php`

```blade
<x-mail::message>
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
    $supportEmail = getSetting('support_email', config('mail.from.address'));
@endphp

<div style="text-align: center; margin-bottom: 30px;">
    @if($appLogo)
        <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 150px;">
    @endif
</div>

# New Exam Assignment

Hello **{{ $user->name }}**,

You have been assigned a new examination to complete.

## Exam Details
- **Exam Name:** {{ $exam->title }}
- **Description:** {{ $exam->description }}
- **Total Questions:** {{ $exam->questions }}
- **Duration:** {{ $exam->duration }} minutes
- **Pass Mark:** {{ $exam->pass_mark }}%
- **Assigned Date:** {{ $assignedDate->format('d M Y') }}
@if($deadline)
- **Deadline:** {{ $deadline->format('d M Y, H:i') }}
@endif

## Instructions
{{ $exam->instruction }}

## Important Information
- You have 3 attempts to complete this examination
- Your score will be recorded in your profile
- Results will be sent to you via email
- Contact support if you need any assistance

<x-mail::button :url="$examUrl">
Start Examination
</x-mail::button>

---

If you have any questions, please contact us at {{ $supportEmail }}

Thanks,<br>
{{ $appName }} Team
</x-mail::message>
```

---

## 📧 Template 4: Exam Reminder (Approaching Deadline)

**File:** `resources/views/emails/exam-reminder.blade.php`

```blade
<x-mail::message>
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
    $supportEmail = getSetting('support_email', config('mail.from.address'));
@endphp

<div style="text-align: center; margin-bottom: 30px;">
    @if($appLogo)
        <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 150px;">
    @endif
</div>

# ⏰ Exam Deadline Reminder

Hello **{{ $user->name }}**,

This is a reminder that your examination deadline is approaching.

## Exam Details
- **Exam Name:** {{ $userExam->exam->title }}
- **Deadline:** {{ $userExam->scheduled_deadline->format('d M Y, H:i') }}
- **Days Remaining:** {{ $daysRemaining }} day(s)
- **Attempts Used:** {{ $userExam->attempts_used }} of 3

## Current Status
@if($daysRemaining <= 1)
<div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 15px; margin: 20px 0;">
    <strong>⚠️ URGENT:</strong> Your examination deadline is very soon! Please complete it as soon as possible.
</div>
@elseif($daysRemaining <= 3)
<div style="background-color: #FEF3C7; border-left: 4px solid #F59E0B; padding: 15px; margin: 20px 0;">
    <strong>📌 REMINDER:</strong> Please complete your examination before the deadline.
</div>
@endif

## What to Do
1. Log in to your account
2. Navigate to "My Exams" section
3. Click "Start Examination"
4. Complete all questions
5. Submit your answers

<x-mail::button :url="$examUrl">
Complete Examination Now
</x-mail::button>

---

If you have any questions or need assistance, please contact us at {{ $supportEmail }}

Best regards,<br>
{{ $appName }} Team
</x-mail::message>
```

---

## 📧 Template 5: Password Reset Email

**File:** `resources/views/emails/password-reset.blade.php`

```blade
<x-mail::message>
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
@endphp

<div style="text-align: center; margin-bottom: 30px;">
    @if($appLogo)
        <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 150px;">
    @endif
</div>

# Reset Your Password

Hello {{ $user->name }},

We received a request to reset your password. If you didn't make this request, you can ignore this email.

## Reset Instructions

Click the button below to reset your password:

<x-mail::button :url="$resetUrl">
Reset Password
</x-mail::button>

This password reset link will expire in {{ config('auth.passwords.users.expire') }} minutes.

## Security Note
- This link is for you only
- Never share this link with anyone
- If you didn't request this, your account may be compromised

---

If you continue to have problems, contact our support team.

Thanks,<br>
{{ $appName }} Team
</x-mail::message>
```

---

## 📧 Template 6: Admin Notification (Exam Status Update)

**File:** `resources/views/emails/admin-exam-status.blade.php`

```blade
<x-mail::message>
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
@endphp

<div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #E5E7EB; padding-bottom: 20px;">
    @if($appLogo)
        <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 150px;">
    @else
        <h1 style="color: #005A9C;">{{ $appName }} - Admin Notification</h1>
    @endif
</div>

# Exam Completion Notification

Hello Admin,

A user has completed an examination. Please review the details below:

## Student Information
- **Name:** {{ $student->name }}
- **Email:** {{ $student->email }}
- **Company:** {{ $student->company }}

## Examination Details
- **Exam Name:** {{ $result->exam->title }}
- **Completion Date:** {{ $result->finished_at->format('d M Y, H:i') }}
- **Total Attempts:** {{ $result->attempts_used }}
- **Score:** {{ $result->scores }}%
- **Pass Mark:** {{ $result->exam->pass_mark }}%

## Result Status
@if($status === 'passed')
<div style="background-color: #D1FAE5; border-left: 4px solid #10B981; padding: 15px; margin: 20px 0;">
    <strong>✅ PASSED</strong> - Student has successfully passed the examination.
</div>
@else
<div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 15px; margin: 20px 0;">
    <strong>❌ FAILED</strong> - Student did not pass the examination.
</div>
@endif

## Required Actions
@if($status === 'passed')
- Review student's performance
- Prepare certificate
- Update student records
@else
- Review feedback for student
- Prepare retest details
- Update student records
@endif

<x-mail::button :url="$adminDashboardUrl">
View in Admin Panel
</x-mail::button>

---

This is an automated notification. Do not reply to this email.

{{ $appName }} - Administration System
</x-mail::message>
```

---

## 🔄 Cara Menggunakan Template Ini

### 1. Create Mail Class (Contoh untuk Exam Assigned)

**File:** `app/Mail/ExamAssignedMail.php`

```php
<?php

namespace App\Mail;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExamAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $exam;
    public $assignedDate;
    public $deadline;
    public $examUrl;

    public function __construct(User $user, Exam $exam, $assignedDate, $deadline = null)
    {
        $this->user = $user;
        $this->exam = $exam;
        $this->assignedDate = $assignedDate;
        $this->deadline = $deadline;
        $this->examUrl = route('exam.detail', $exam->id);
    }

    public function build()
    {
        return $this->markdown('emails.exam-assigned');
    }
}
```

### 2. Send Email dari Controller

```php
use App\Mail\ExamAssignedMail;
use Illuminate\Support\Facades\Mail;

public function assignExam(Request $request)
{
    $exam = Exam::find($request->exam_id);
    $user = User::find($request->user_id);
    $deadline = now()->addDays(7);

    Mail::to($user->email)->send(
        new ExamAssignedMail($user, $exam, now(), $deadline)
    );

    return response()->json(['success' => true]);
}
```

### 3. Send via Queue (For Better Performance)

```php
Mail::to($user->email)->queue(
    new ExamAssignedMail($user, $exam, now(), $deadline)
);
```

---

## ✅ Testing Template

```bash
php artisan tinker

# Test Exam Result Email
>>> $student = App\Models\User::first();
>>> $result = App\Models\UserExam::with('exam')->first();
>>> Mail::send(new \App\Mail\ExamResultMail($student, $result, 'passed'));

# Test Welcome Email
>>> $user = App\Models\User::first();
>>> Mail::send(new \App\Mail\WelcomeMail($user));
```

---

**Last Updated:** December 10, 2025

