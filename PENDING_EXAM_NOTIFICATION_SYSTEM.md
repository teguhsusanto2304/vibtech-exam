# Sistem Notifikasi Ujian Tertunda (Pending Exam Notification System)

## 📋 Overview

Sistem otomatis untuk mengirimkan notifikasi kepada admin ketika ada user yang tidak menyelesaikan ujian dalam batas waktu yang ditentukan.

## ✨ Fitur

- ✅ Notifikasi otomatis 3 hari sebelum deadline
- ✅ Notifikasi otomatis 1 hari sebelum deadline
- ✅ Notifikasi otomatis ketika deadline sudah terlewat
- ✅ Email dan Database notification
- ✅ Scheduled menggunakan Laravel Scheduler
- ✅ Tracking notifikasi yang sudah dikirim
- ✅ Helper functions untuk check status exam

## 🏗️ Komponen

### 1. Database Migration
**File:** `database/migrations/2025_12_06_000001_add_scheduled_deadline_to_user_exams_table.php`

```bash
php artisan migrate
```

**Kolom yang ditambahkan:**
- `scheduled_deadline` - Batas waktu user harus mengerjakan ujian
- `notification_sent` - Flag untuk tracking notifikasi sudah dikirim

### 2. Notification Class
**File:** `app/Notifications/PendingExamReminder.php`

Mengirimkan notifikasi via:
- Email (ke admin)
- Database (dalam notification table)

**Data yang dikirim:**
```php
[
    'type' => 'pending_exam_reminder',
    'user_id' => 123,
    'user_name' => 'John Doe',
    'exam_title' => 'Math Final Exam',
    'scheduled_deadline' => '2025-12-10 17:00:00',
    'days_overdue' => 2,
    'message' => 'User John Doe has not completed exam...'
]
```

### 3. Console Command
**File:** `app/Console/Commands/SendPendingExamReminders.php`

**Signature:**
```bash
php artisan exam:send-pending-reminders
    {--days=3 : Number of days before deadline}
    {--days-after=1 : Number of days after deadline}
```

**Contoh Penggunaan:**
```bash
# Send reminders 3 days before deadline
php artisan exam:send-pending-reminders --days=3

# Send reminders 1 day before deadline
php artisan exam:send-pending-reminders --days=1

# Send reminders untuk overdue exams
php artisan exam:send-pending-reminders --days-after=3
```

### 4. Scheduler Configuration
**File:** `app/Console/Kernel.php`

**Schedule yang terjadwal:**
```
09:00 AM (Daily) → Send reminder 3 days before deadline
05:00 PM (Daily) → Send reminder 1 day before deadline
10:00 AM (Daily) → Check untuk overdue exams
```

## 🔧 Setup Instructions

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Start Queue Worker (untuk email)
```bash
php artisan queue:work
```

Atau jika menggunakan synchronous:
```
QUEUE_CONNECTION=sync
```

### Step 3: Setup Scheduler

#### Option A: Linux/Mac (Cron)
Tambahkan ke crontab:
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

#### Option B: Windows (Task Scheduler)
1. Buka Task Scheduler
2. Create Basic Task
3. Set trigger untuk setiap menit
4. Action: `php.exe` dengan argument `D:\path\to\artisan schedule:run`

#### Option C: Development Testing
```bash
# Run scheduler untuk development
php artisan schedule:work
```

## 📊 Workflow

```
┌─────────────────────────────────────────────────┐
│  Daily Scheduler Run (Every Minute)             │
└────────────────┬────────────────────────────────┘
                 │
                 ├─── 09:00 AM ────────┐
                 │                      │
                 ├─── 05:00 PM ────────┐
                 │                      │
                 └─── 10:00 AM ────────┐
                                        │
                                        ▼
                ┌───────────────────────────────┐
                │ SendPendingExamReminders Cmd  │
                └───────────┬───────────────────┘
                            │
                ┌───────────┼───────────┐
                │           │           │
                ▼           ▼           ▼
        ┌────────────┐ ┌────────────┐ ┌────────────┐
        │Approaching │ │ 1 Day Left │ │  Overdue   │
        │ 3 Days     │ │   Check    │ │   Check    │
        │  Check     │ │            │ │            │
        └─────┬──────┘ └──────┬─────┘ └──────┬─────┘
              │               │              │
              └───────┬───────┴────────┬─────┘
                      │               │
                      ▼               ▼
            ┌──────────────────┐ ┌──────────────┐
            │ Send Notification│ │Send Overdue  │
            │ to All Admins    │ │ Reminder     │
            └──────────────────┘ └──────────────┘
                      │                   │
                      └───────┬───────────┘
                              │
                              ▼
                    ┌────────────────────┐
                    │ Mark notification_ │
                    │  sent = true       │
                    └────────────────────┘
```

## 📝 Model Relations

### UserExam Model
```php
// Di app/Models/UserExam.php tambahkan:

/**
 * Get the exam
 */
public function exam()
{
    return $this->belongsTo(Exam::class);
}

/**
 * Get the user
 */
public function user()
{
    return $this->belongsTo(User::class);
}

/**
 * Scope untuk pending exams
 */
public function scopePending($query)
{
    return $query->where('data_status', 'pending');
}

/**
 * Scope untuk approaching deadline
 */
public function scopeApproachingDeadline($query, $days = 3)
{
    return $query->pending()
        ->whereNotNull('scheduled_deadline')
        ->whereBetween('scheduled_deadline', [
            now(),
            now()->addDays($days)
        ]);
}

/**
 * Scope untuk overdue
 */
public function scopeOverdue($query)
{
    return $query->pending()
        ->whereNotNull('scheduled_deadline')
        ->where('scheduled_deadline', '<', now());
}
```

## 💻 Helper Functions

**File:** `app/Helpers/SettingsHelper.php`

### 1. getPendingExams()
```php
$pendingExams = getPendingExams($user);
```

### 2. getOverdueExams()
```php
$overdueExams = getOverdueExams();
```

### 3. isApproachingDeadline()
```php
if (isApproachingDeadline($userExam, 3)) {
    // Exam deadline approaching
}
```

### 4. isExamOverdue()
```php
if (isExamOverdue($userExam)) {
    // Exam is overdue
}
```

### 5. daysRemaining()
```php
$days = daysRemaining($userExam);
echo "Days remaining: {$days}";
```

## 📧 Email Notification Template

Admin akan menerima email seperti ini:

```
Subject: ⏰ User Exam Reminder - Pending Assignment

Hello Admin,

A user has not completed their assigned exam within the scheduled deadline.

User Details:
- Name: John Doe
- Email: john@example.com

Exam Details:
- Exam: Math Final Exam
- Assigned Date: 05 Dec 2025 10:30
- Deadline: 10 Dec 2025 17:00
- Days Overdue: 2

[View User Exams Button]

Please follow up with the user to ensure they complete the exam.

Thank you!
```

## 📊 Database Notification Example

```json
{
    "type": "pending_exam_reminder",
    "user_id": 5,
    "user_name": "John Doe",
    "user_email": "john@example.com",
    "exam_id": 2,
    "exam_title": "Math Final Exam",
    "user_exam_id": 10,
    "scheduled_deadline": "2025-12-10 17:00:00",
    "days_overdue": 2,
    "message": "User John Doe has not completed exam 'Math Final Exam' by the deadline."
}
```

## 🎯 Praktik Penggunaan di Controller

### 1. Assign Exam dengan Deadline
```php
// Di ExamController atau tugas assignment
$userExam = UserExam::create([
    'user_id' => $userId,
    'exam_id' => $examId,
    'data_status' => 'pending',
    'scheduled_deadline' => now()->addDays(7), // 7 hari dari sekarang
    'notification_sent' => false,
]);
```

### 2. Check & Display Pending Exams di Dashboard
```php
// Di AdminController
public function dashboard()
{
    $pendingExams = UserExam::with(['user', 'exam'])
        ->where('data_status', 'pending')
        ->get();
    
    $overdueExams = getOverdueExams();
    $approachingDeadline = $pendingExams->filter(function ($exam) {
        return isApproachingDeadline($exam, 3);
    });

    return view('admin.dashboard', [
        'pendingExams' => $pendingExams,
        'overdueExams' => $overdueExams,
        'approachingDeadline' => $approachingDeadline,
    ]);
}
```

## 🔐 Security & Performance

### 1. Database Indexing
```php
// Di migration, tambahkan indexes:
Schema::table('user_exams', function (Blueprint $table) {
    $table->index('data_status');
    $table->index('scheduled_deadline');
    $table->index('notification_sent');
    $table->index(['data_status', 'scheduled_deadline']);
});
```

### 2. Prevent Duplicate Notifications
```php
// Flag 'notification_sent' mencegah pengiriman duplikat
$userExam->notification_sent = true;
$userExam->save();
```

### 3. Queue Configuration
```env
# .env
QUEUE_CONNECTION=database
# atau untuk async:
QUEUE_CONNECTION=redis
```

## 🐛 Troubleshooting

### Issue 1: Scheduler tidak berjalan
**Solusi:**
```bash
# Check jika command terdaftar
php artisan list

# Test command manual
php artisan exam:send-pending-reminders --days=3
```

### Issue 2: Email tidak terkirim
**Solusi:**
```bash
# Check queue
php artisan queue:work

# Check email configuration di .env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
```

### Issue 3: Notifikasi database tidak tersimpan
**Solusi:**
```bash
# Check notifications table
php artisan migrate
# Pastikan user model has: use Notifiable;
```

## 📚 Testing

### Manual Test Command
```bash
# Test sending reminders
php artisan exam:send-pending-reminders --days=3

# Test untuk specific days after deadline
php artisan exam:send-pending-reminders --days-after=3

# Verbose output
php artisan exam:send-pending-reminders --days=3 --verbose
```

### View Notifications di Database
```php
php artisan tinker

>>> $admin = User::where('role', 'admin')->first();
>>> $admin->notifications()->latest()->take(10)->get();
>>> $admin->unreadNotifications()->count();
```

## 🚀 Production Checklist

- [ ] Migration dijalankan: `php artisan migrate`
- [ ] Queue worker berjalan
- [ ] Scheduler terjadwal di server cron
- [ ] Email configuration sudah setup
- [ ] Database notifications table exist
- [ ] Test manual command: `php artisan exam:send-pending-reminders`
- [ ] Test email receiving dari admin account
- [ ] Check logs untuk errors

## 📖 Additional Resources

- Laravel Scheduling: https://laravel.com/docs/scheduling
- Notifications: https://laravel.com/docs/notifications
- Queues: https://laravel.com/docs/queues

---

**Status:** ✅ Implementation Complete  
**Version:** 1.0.0  
**Last Updated:** December 6, 2025

