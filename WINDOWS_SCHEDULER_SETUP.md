# Setup Laravel Scheduler di Windows 11

## 📋 Overview

Panduan lengkap untuk mengsetup Laravel Scheduler di Windows 11 sehingga command `php artisan schedule:run` berjalan secara otomatis sesuai jadwal yang ditentukan.

## 🎯 Metode Setup (Pilih Salah Satu)

### Metode 1: Task Scheduler (Recommended - Built-in)
### Metode 2: Batch File + Scheduled Task
### Metode 3: Docker Compose (Advanced)

---

## 🔧 Metode 1: Task Scheduler (Recommended)

### Step 1: Buka Task Scheduler

**Opsi A - Via Run Dialog:**
1. Tekan `Win + R`
2. Ketik: `taskschd.msc`
3. Tekan `Enter`

**Opsi B - Via Settings:**
1. Klik `Start` → search `Task Scheduler`
2. Klik `Task Scheduler`

![Image placeholder - Task Scheduler UI]

### Step 2: Create Basic Task

1. Di panel kanan, klik **`Create Basic Task...`**
2. Isi form:
   - **Name:** `Laravel Scheduler - Exam Reminders`
   - **Description:** `Runs Laravel scheduler to send pending exam reminders to admins`
   - Klik `Next`

### Step 3: Set Trigger (Jadwal)

**Opsi untuk memilih:**

#### Option A: Every Minute (Recommended untuk scheduler Laravel)
```
Trigger: On a schedule
Frequency: Daily
Time: 00:00:00
Repeat task every: 1 minute
Duration: Indefinitely
```

**Steps:**
1. Pilih `One time`
2. Klik `Next`
3. Pilih `Daily`
4. Set time: `00:00:00` (jam berapa saja, misal `09:00:00`)
5. Check: `Repeat task every: 1 minute`
6. Check: `for a duration of: Indefinitely`
7. Klik `Next`

#### Option B: Multiple Times (Lebih efisien)
```
09:00 AM - Check exam 3 hari sebelum deadline
05:00 PM - Check exam 1 hari sebelum deadline
10:00 AM - Check exam overdue
```

Buat 3 tasks terpisah untuk setiap waktu.

### Step 4: Set Action (Program yang dijalankan)

1. Pilih `Start a program`
2. Isi field:

```
Program/script: C:\php\php.exe
(sesuaikan dengan lokasi PHP Anda)

Add arguments (optional):
D:\project\Laravel\vibtech_exam\artisan schedule:run
(sesuaikan dengan path project Anda)

Start in (optional):
D:\project\Laravel\vibtech_exam
```

**Contoh Screenshot:**
```
Program/script: C:\php\php.exe
Add arguments: D:\project\Laravel\vibtech_exam\artisan schedule:run >> D:\project\Laravel\vibtech_exam\storage\logs\scheduler.log 2>&1
Start in: D:\project\Laravel\vibtech_exam
```

3. Klik `Next`

### Step 5: Review & Finish

1. Review semua setting
2. Check: `Open the Properties dialog for this task when I click Finish`
3. Klik `Finish`

### Step 6: Configure Advanced Options (Important!)

Di Properties dialog yang terbuka:

**Tab "General":**
- ✅ Check: `Run with highest privileges`
- ✅ Check: `Run whether user is logged in or not`

**Tab "Conditions":**
- Uncheck: `Start the task only if the computer is on AC power`
- Uncheck: `Stop the task if the computer switches to battery power`

**Tab "Settings":**
- ✅ Check: `Allow task to be run on demand`
- ✅ Check: `Run task as soon as possible after a scheduled start is missed`
- Set: `If the task fails, restart every: 5 minutes`
- Set: `Attempt to restart up to: 10 times`

3. Klik `OK`

---

## 🚀 Metode 2: Batch File + Scheduled Task

### Step 1: Locate PHP Path

Buka PowerShell dan jalankan:
```powershell
where php
```

Output akan seperti:
```
C:\php\php.exe
```

Catat path ini.

### Step 2: Create Batch File

1. Buka Notepad
2. Paste script berikut:

```batch
@echo off
REM Set timezone
set TZ=Asia/Jakarta

REM Change to project directory
cd /d D:\project\Laravel\vibtech_exam

REM Run Laravel scheduler
C:\php\php.exe artisan schedule:run >> storage\logs\scheduler.log 2>&1

REM Exit
exit /b 0
```

**Penjelasan:**
- `@echo off` - Hide command output
- `set TZ=Asia/Jakarta` - Set timezone (sesuaikan dengan timezone Anda)
- `cd /d D:\project\Laravel\vibtech_exam` - Change directory ke project
- `C:\php\php.exe` - Lokasi PHP executable
- `artisan schedule:run` - Command yang dijalankan
- `>> storage\logs\scheduler.log` - Append output ke log file
- `2>&1` - Redirect error ke log juga

3. Save as: `D:\project\Laravel\vibtech_exam\scheduler.bat`
   - File name: `scheduler`
   - File type: `All Files (*.*)`
   - Encoding: `UTF-8 or ANSI`

### Step 3: Test Batch File

1. Buka Command Prompt (CMD)
2. Navigate ke directory:
   ```cmd
   cd D:\project\Laravel\vibtech_exam
   ```

3. Run batch file:
   ```cmd
   scheduler.bat
   ```

4. Check log:
   ```cmd
   type storage\logs\scheduler.log
   ```

Jika tidak ada error, lanjut ke Step 4.

### Step 4: Create Scheduled Task untuk Batch File

1. Buka Task Scheduler (seperti Metode 1)
2. Create Basic Task:
   - **Name:** `Laravel Scheduler Batch`
   - **Description:** `Runs Laravel scheduler via batch file`

3. Set Trigger:
   - **Frequency:** Daily
   - **Time:** 09:00:00 (atau 00:00:00)
   - **Repeat every:** 1 minute
   - **For duration:** Indefinitely

4. Set Action:
   ```
   Program/script: D:\project\Laravel\vibtech_exam\scheduler.bat
   Add arguments: (kosongkan)
   Start in: D:\project\Laravel\vibtech_exam
   ```

5. Set Advanced Options (seperti Metode 1)

---

## 🐛 Troubleshooting

### Issue 1: Task tidak jalan sama sekali

**Check:**
```powershell
# Buka PowerShell as Admin
Get-ScheduledTask -TaskName "Laravel Scheduler*" | Select-Object TaskName, State

# Output akan show State: Running atau Disabled
```

**Solusi:**
```powershell
# Enable task
Enable-ScheduledTask -TaskName "Laravel Scheduler - Exam Reminders"

# Run manually untuk test
Start-ScheduledTask -TaskName "Laravel Scheduler - Exam Reminders"

# Check hasil
Get-ScheduledTaskInfo -TaskName "Laravel Scheduler - Exam Reminders"
```

### Issue 2: PHP not found

**Error:**
```
php.exe tidak ditemukan
```

**Solusi:**

Step 1: Cari lokasi PHP
```powershell
where php
# atau
Get-Command php
```

Step 2: Update task dengan path yang benar

Jika tidak ketemu, install PHP atau tambahkan ke PATH:

1. Buka: `Settings` → `System` → `About`
2. Klik: `Advanced system settings`
3. Klik: `Environment Variables`
4. Di "System variables", klik `Path` → `Edit`
5. Klik `New` dan add: `C:\php` (sesuaikan dengan lokasi PHP Anda)
6. Klik `OK` dan restart computer

### Issue 3: Permission Denied

**Error:**
```
Access denied
The directory name is invalid
```

**Solusi:**

1. Edit task yang bermasalah
2. Tab "General":
   - ✅ Check: `Run with highest privileges`
3. Tab "Advanced":
   - ✅ Check: `Run task as soon as possible after a scheduled start is missed`

### Issue 4: Logs tidak terupdate

**Check:**
```powershell
# Navigate ke project
cd D:\project\Laravel\vibtech_exam

# Check if logs dir exists
Test-Path storage\logs

# View recent logs
Get-Content storage\logs\scheduler.log -Tail 20
```

**Solusi:**

Pastikan `storage/logs` folder writable:
1. Right-click folder `storage`
2. Properties → Security → Edit
3. Select your user → Allow "Modify"
4. Klik OK

### Issue 5: Task Scheduler show "Last Run Result: 0x1"

**Penjelasan:** Task gagal karena akses/permission issue

**Solusi:**
1. Buka Properties task
2. Tab "General":
   - ✅ Check: `Run with highest privileges`
   - ✅ Check: `Run whether user is logged in or not`
3. Test manual: Right-click task → `Run`
4. Check log file untuk error detail

---

## 📊 Verifikasi Setup

### Check 1: Task Status di PowerShell

```powershell
# List semua Laravel scheduler tasks
Get-ScheduledTask | Where-Object {$_.TaskName -like "*Laravel*"}

# Output:
# TaskPath                                       TaskName                         State
# --------                                       --------                         -----
# \                                              Laravel Scheduler - Exam Remi... Running
```

### Check 2: Last Run Timestamp

```powershell
Get-ScheduledTaskInfo -TaskName "Laravel Scheduler - Exam Reminders" | Format-List

# Output:
# LastRunTime        : 12/7/2025 9:05:00 AM
# LastRunResult      : 0 (0 = Success)
# NextRunTime        : 12/7/2025 9:06:00 AM
```

### Check 3: Check Log File

```powershell
# View last 20 lines
Get-Content D:\project\Laravel\vibtech_exam\storage\logs\scheduler.log -Tail 20

# atau gunakan tail command jika punya Git installed
tail -f D:\project\Laravel\vibtech_exam\storage\logs\scheduler.log
```

**Contoh Log Sukses:**
```
[2025-12-07 09:05:00] Scheduler checking pending exams...
[2025-12-07 09:05:00] Notifications sent: 2
[2025-12-07 09:05:00] Overdue notifications: 1
[2025-12-07 09:05:00] Process completed successfully
```

---

## 📈 Multiple Scheduler Tasks Setup

Untuk run scheduler di multiple times:

### Setup 3 Tasks dengan waktu berbeda:

**Task 1: Morning Check (09:00)**
```
Name: Laravel Scheduler - 09:00
Program: C:\php\php.exe
Arguments: D:\project\Laravel\vibtech_exam\artisan schedule:run
Trigger: Daily at 09:00 AM, repeat every 1 minute
```

**Task 2: Evening Check (17:00)**
```
Name: Laravel Scheduler - 17:00
Program: C:\php\php.exe
Arguments: D:\project\Laravel\vibtech_exam\artisan schedule:run
Trigger: Daily at 17:00 (5:00 PM), repeat every 1 minute
```

**Task 3: Morning Overdue Check (10:00)**
```
Name: Laravel Scheduler - 10:00
Program: C:\php\php.exe
Arguments: D:\project\Laravel\vibtech_exam\artisan schedule:run
Trigger: Daily at 10:00 AM, repeat every 1 minute
```

---

## 🎯 Recommended Settings untuk Production

### Kernel.php Schedule

```php
protected function schedule(Schedule $schedule): void
{
    // 09:00 - Cek 3 hari sebelum deadline
    $schedule->command('exam:send-pending-reminders --days=3')
        ->dailyAt('09:00')
        ->name('pending-exam-reminders-3days')
        ->withoutOverlapping(10) // Max 10 min runtime
        ->onFailure(function () {
            // Log atau send alert
            Log::error('Scheduler failed at 09:00');
        });

    // 17:00 - Cek 1 hari sebelum deadline
    $schedule->command('exam:send-pending-reminders --days=1')
        ->dailyAt('17:00')
        ->name('pending-exam-reminders-1day')
        ->withoutOverlapping(10);

    // 10:00 - Cek overdue exams
    $schedule->command('exam:send-pending-reminders --days-after=3')
        ->dailyAt('10:00')
        ->name('pending-exam-reminders-overdue')
        ->withoutOverlapping(10);
}
```

---

## 🔐 Security Best Practices

### 1. Create Dedicated User (Optional tapi recommended)

```powershell
# Buat local user untuk scheduler
$username = "LaravelScheduler"
$password = ConvertTo-SecureString "ComplexPassword123!@#" -AsPlainText -Force
New-LocalUser -Name $username -Password $password -FullName "Laravel Scheduler Service"

# Add ke group "Users"
Add-LocalGroupMember -Group "Users" -Member $username
```

### 2. Set Folder Permissions

```powershell
# Give user akses ke project folder
$acl = Get-Acl "D:\project\Laravel\vibtech_exam"
$permission = New-Object System.Security.AccessControl.FileSystemAccessRule(
    "LaravelScheduler",
    "Modify",
    "ContainerInherit,ObjectInherit",
    "None",
    "Allow"
)
$acl.SetAccessRule($permission)
Set-Acl "D:\project\Laravel\vibtech_exam" $acl
```

### 3. Configure Task dengan User Credentials

1. Edit task di Task Scheduler
2. Tab "General":
   - Change user: Pilih `LaravelScheduler`
   - Input password
3. Save

---

## 📝 Complete Setup Checklist

- [ ] PHP path ditentukan dan accessible
- [ ] Project path ditentukan dengan benar
- [ ] Batch file dibuat (optional)
- [ ] Scheduled Task dibuat di Task Scheduler
- [ ] Advanced options dikonfigurasi
- [ ] "Run with highest privileges" dicheck
- [ ] Timezone diatur dengan benar
- [ ] Manual test berhasil
- [ ] Log file dikecek untuk hasil run
- [ ] Task status menunjukkan "Running"
- [ ] LastRunResult menunjukkan "0" (success)

---

## 🚀 Testing & Verification

### Quick Test Command

```powershell
# Jalankan scheduler manual
C:\php\php.exe D:\project\Laravel\vibtech_exam\artisan schedule:run

# Atau dengan laravel command jika sudah setup artisan alias
cd D:\project\Laravel\vibtech_exam
php artisan schedule:run
```

### Check Notifications Dikirim

```php
php artisan tinker

>>> $admin = User::where('role', 'admin')->first();
>>> $admin->unreadNotifications()->count();

// Atau check di database
>>> DB::table('notifications')->latest()->first();
```

### Monitor Logs Real-time

```powershell
# Install tail atau gunakan PowerShell tail
Get-Content D:\project\Laravel\vibtech_exam\storage\logs\scheduler.log -Wait -Tail 10
```

---

## 📚 Additional Resources

- Laravel Scheduling Docs: https://laravel.com/docs/scheduling
- Windows Task Scheduler: https://learn.microsoft.com/en-us/windows/desktop/TaskSchd/task-scheduler-start-page
- PowerShell ScheduledTask: https://learn.microsoft.com/en-us/powershell/module/scheduledtasks

---

## 🎓 Example: Complete Setup dari A-Z

### 1. Check PHP
```powershell
where php
# Output: C:\php\php.exe
```

### 2. Create Batch File

**File: `D:\project\Laravel\vibtech_exam\scheduler.bat`**
```batch
@echo off
cd /d D:\project\Laravel\vibtech_exam
C:\php\php.exe artisan schedule:run >> storage\logs\scheduler.log 2>&1
```

### 3. Open Task Scheduler
```
Win + R → taskschd.msc
```

### 4. Create Basic Task
- Name: `Laravel Exam Scheduler`
- Description: `Sends pending exam reminders to admins`

### 5. Set Trigger
- Frequency: Daily
- Time: 00:00:00
- Repeat every: 1 minute
- Duration: Indefinitely

### 6. Set Action
```
Program: C:\php\php.exe
Arguments: D:\project\Laravel\vibtech_exam\artisan schedule:run
Start in: D:\project\Laravel\vibtech_exam
```

### 7. Configure Properties
- ✅ Run with highest privileges
- ✅ Run whether user is logged in or not
- Restart on failure: 5 minutes

### 8. Test
```powershell
Start-ScheduledTask -TaskName "Laravel Exam Scheduler"
```

### 9. Verify
```powershell
Get-ScheduledTaskInfo -TaskName "Laravel Exam Scheduler"
Get-Content D:\project\Laravel\vibtech_exam\storage\logs\scheduler.log -Tail 10
```

---

**Status:** ✅ Complete Setup Guide  
**Version:** 1.0.0  
**Last Updated:** December 7, 2025

