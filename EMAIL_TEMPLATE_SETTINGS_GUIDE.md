# Panduan: Mengubah Template Email ke Logo dari Settings

## 📋 Overview

Panduan ini menjelaskan cara mengubah template email Laravel untuk menggunakan logo dinamis dari table `settings` daripada hardcoded logo.

---

## ✅ Perubahan yang Dilakukan

### File: `resources/views/emails/exam-result.blade.php`

#### BEFORE:
```blade
<x-mail::message>
# Hello {{ $student->name }},

Please view your examination result below.

- **Candidate Name:** {{ $student->name }}
...
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
```

#### AFTER:
```blade
<x-mail::message>
{{-- Logo dari Settings Table --}}
@php
    $appLogo = getSetting('app_logo', '/images/logo.png');
    $appName = getSetting('app_name', config('app.name'));
@endphp

<div style="text-align: center; margin-bottom: 30px;">
    @if($appLogo && $appLogo !== '/images/logo.png')
        <img src="{{ $appLogo }}" alt="{{ $appName }} Logo" style="max-width: 200px; height: auto;">
    @else
        <div style="font-size: 24px; font-weight: bold; color: #005A9C;">
            {{ $appName }}
        </div>
    @endif
</div>

# Examination Result Notification
...
</x-mail::message>
```

---

## 🔑 Key Changes

### 1. Get Logo & App Name dari Settings
```blade
@php
    $appLogo = getSetting('app_logo', '/images/logo.png');
    $appName = getSetting('app_name', config('app.name'));
@endphp
```

**Penjelasan:**
- `getSetting('app_logo')` - Ambil logo dari table settings
- Fallback ke `/images/logo.png` jika tidak ada
- `getSetting('app_name')` - Ambil nama app dari settings
- Fallback ke `config('app.name')` jika tidak ada

### 2. Render Logo di Email
```blade
<div style="text-align: center; margin-bottom: 30px;">
    @if($appLogo && $appLogo !== '/images/logo.png')
        <img src="{{ $appLogo }}" alt="{{ $appName }} Logo" style="max-width: 200px; height: auto;">
    @else
        <div style="font-size: 24px; font-weight: bold; color: #005A9C;">
            {{ $appName }}
        </div>
    @endif
</div>
```

**Penjelasan:**
- Jika ada custom logo, render sebagai gambar
- Jika tidak, render nama app sebagai text
- Centered dengan margin bawah 30px
- Max width 200px untuk responsive

### 3. Professional Layout
```blade
## Candidate Information
- **Full Name:** {{ $student->name }}
- **Company:** {{ $student->company ?? 'N/A' }}

## Examination Details
- **Examination Name:** {{ $result->exam->title }}

## Result Status
@if($status === 'passed')
    <div style="background-color: #D1FAE5; ...">✅ PASSED</div>
@else
    <div style="background-color: #FEE2E2; ...">❌ FAILED</div>
@endif
```

---

## 🎯 Template Email Lain yang Perlu Diupdate

### 1. Notification Email Template

**File:** `resources/views/emails/notification.blade.php` (jika ada)

```blade
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
    $supportEmail = getSetting('support_email', config('mail.from.address'));
@endphp

<x-mail::message>
    <div style="text-align: center; margin-bottom: 30px;">
        @if($appLogo)
            <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 150px;">
        @else
            <h2 style="color: #005A9C; margin: 0;">{{ $appName }}</h2>
        @endif
    </div>

    <!-- Email content here -->

    Thanks,<br>
    {{ $appName }}<br>
    {{ $supportEmail }}
</x-mail::message>
```

### 2. User Welcome Email

**File:** `resources/views/emails/welcome.blade.php`

```blade
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
@endphp

<x-mail::message>
    <div style="text-align: center; margin-bottom: 30px;">
        @if($appLogo)
            <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 150px;">
        @endif
    </div>

    # Welcome to {{ $appName }}!

    Hello {{ $user->name }},

    Thank you for joining us...

    Best regards,<br>
    The {{ $appName }} Team
</x-mail::message>
```

### 3. Password Reset Email

**File:** `resources/views/emails/reset-password.blade.php`

```blade
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
@endphp

<x-mail::message>
    <div style="text-align: center; margin-bottom: 30px;">
        @if($appLogo)
            <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 150px;">
        @endif
    </div>

    # Reset Your Password

    You are receiving this email because we received a password reset request...
</x-mail::message>
```

---

## 🔧 Setup Requirement

### 1. Helper Function Harus Tersedia

Pastikan `getSetting()` sudah didaftarkan di `composer.json`:

```json
{
    "autoload": {
        "files": [
            "app/Helpers/SettingsHelper.php"
        ]
    }
}
```

Run:
```bash
composer dump-autoload
```

### 2. Helper Function Content

**File:** `app/Helpers/SettingsHelper.php`

```php
<?php

function getSetting($key, $default = null)
{
    return \App\Models\Setting::getValue($key, $default);
}

function appLogo()
{
    return getSetting('app_logo', '/images/logo.png');
}

function appName()
{
    return getSetting('app_name', config('app.name'));
}

function supportEmail()
{
    return getSetting('support_email', env('MAIL_FROM_ADDRESS'));
}
```

### 3. Settings Table Harus Ada

```bash
php artisan migrate
```

### 4. Settings Data Harus Diisi

**Via Admin Panel:**
1. Go to: `/admin/settings`
2. Upload logo
3. Set app name
4. Set support email

**Atau via Tinker:**
```php
php artisan tinker

>>> App\Models\Setting::setValue('app_logo', '/storage/images/logo.png');
>>> App\Models\Setting::setValue('app_name', 'Vibtech Exam');
>>> App\Models\Setting::setValue('support_email', 'support@vibtech.com');
```

---

## 📧 Email Preview

### PASSED Exam Email

```
┌─────────────────────────────────────────┐
│  [LOGO IMAGE]                           │
│                                         │
│  Examination Result Notification        │
│                                         │
│  Hello John Doe,                        │
│                                         │
│  Candidate Information                  │
│  • Full Name: John Doe                  │
│  • Company: PT. ABC Indonesia           │
│  • Email: john@example.com              │
│                                         │
│  Examination Details                    │
│  • Name: Math Final Exam                │
│  • Date: 10 Dec 2025, 14:30            │
│  • Attempts: 2 of 3                     │
│  • Your Score: 85%                      │
│  • Passing Score: 70%                   │
│                                         │
│  ✅ PASSED                              │
│  Congratulations! You have passed...    │
│                                         │
│  Next Steps:                            │
│  • Certificate will be issued shortly   │
│  • Check email for certificate          │
│                                         │
│  Best regards,                          │
│  Vibtech Exam                           │
│  support@vibtech.com                    │
└─────────────────────────────────────────┘
```

### FAILED Exam Email

```
┌─────────────────────────────────────────┐
│  [LOGO IMAGE]                           │
│                                         │
│  Examination Result Notification        │
│                                         │
│  Hello Jane Smith,                      │
│                                         │
│  Candidate Information                  │
│  • Full Name: Jane Smith                │
│  • Company: PT. XYZ Corp                │
│  • Email: jane@example.com              │
│                                         │
│  Examination Details                    │
│  • Name: English Proficiency Test       │
│  • Date: 10 Dec 2025, 15:45            │
│  • Attempts: 3 of 3                     │
│  • Your Score: 62%                      │
│  • Passing Score: 70%                   │
│                                         │
│  ❌ FAILED                              │
│  You did not pass the examination...    │
│                                         │
│  Next Steps:                            │
│  • Review examination feedback          │
│  • Prepare for next attempt             │
│  • Contact: support@vibtech.com         │
└─────────────────────────────────────────┘
```

---

## 🎨 CSS Styling dalam Email

```blade
<!-- Header dengan Logo -->
<div style="
    text-align: center;
    padding: 20px 0;
    border-bottom: 2px solid #E5E7EB;
    margin-bottom: 30px;
">
    @if($appLogo)
        <img src="{{ $appLogo }}" alt="{{ $appName }}" style="
            max-width: 200px;
            height: auto;
            display: block;
            margin: 0 auto;
        ">
    @endif
</div>

<!-- Status Box - PASSED -->
<div style="
    background-color: #D1FAE5;
    border-left: 4px solid #10B981;
    padding: 15px;
    margin: 20px 0;
    border-radius: 4px;
">
    <span style="
        color: #065F46;
        font-weight: bold;
        font-size: 16px;
    ">✅ PASSED</span>
</div>

<!-- Status Box - FAILED -->
<div style="
    background-color: #FEE2E2;
    border-left: 4px solid #EF4444;
    padding: 15px;
    margin: 20px 0;
    border-radius: 4px;
">
    <span style="
        color: #7F1D1D;
        font-weight: bold;
        font-size: 16px;
    ">❌ FAILED</span>
</div>

<!-- Information List -->
<div style="margin: 20px 0;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="border-bottom: 1px solid #E5E7EB;">
            <td style="padding: 10px 0; font-weight: bold;">Full Name:</td>
            <td style="padding: 10px 0;">{{ $student->name }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #E5E7EB;">
            <td style="padding: 10px 0; font-weight: bold;">Company:</td>
            <td style="padding: 10px 0;">{{ $student->company }}</td>
        </tr>
    </table>
</div>
```

---

## 🚀 Testing

### 1. Manual Test via Tinker

```bash
php artisan tinker

>>> $student = \App\Models\User::first();
>>> $result = \App\Models\UserExam::with('exam')->first();
>>> $status = 'passed';
>>> Mail::send(new \App\Mail\ExamResultMail($student, $result, $status));
```

Check inbox untuk verifikasi.

### 2. Database Verification

```sql
-- Check settings data
SELECT * FROM settings WHERE key IN ('app_logo', 'app_name', 'support_email');

-- Check recent emails dalam log
TAIL storage/logs/laravel.log | grep "Mail"
```

### 3. Dev Testing

```bash
# Buka mail catcher (jika installed)
php artisan tinker
>>> $student = User::first();
>>> $result = UserExam::with('exam')->first();
>>> Mail::send(new ExamResultMail($student, $result, 'passed'));

# Atau cek di Mailtrap/Mailgun dashboard
```

---

## ✅ Checklist Implementasi

- [ ] Update `resources/views/emails/exam-result.blade.php`
- [ ] Update other email templates (welcome, password-reset, etc)
- [ ] Verify `getSetting()` helper function tersedia
- [ ] Run `composer dump-autoload`
- [ ] Run `php artisan migrate` untuk settings table
- [ ] Fill settings data via admin panel
- [ ] Test email sending via Tinker
- [ ] Verify logo muncul di email client (Gmail, Outlook, etc)
- [ ] Test fallback text jika logo tidak ada

---

## 📊 Dynamic vs Hardcoded

### ❌ BEFORE (Hardcoded)
```blade
<x-mail::message>
# Hello {{ $student->name }},

Thanks,<br>
{{ config('app.name') }} <!-- Hardcoded dari config -->
```

**Problem:**
- Logo tidak bisa diubah tanpa edit file
- App name harus edit .env
- Sulit untuk multi-tenant/white-label

### ✅ AFTER (Dynamic dari Settings)
```blade
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
@endphp

<!-- Logo dari database -->
<!-- App name dari database -->
```

**Benefits:**
- Logo bisa diubah via admin panel
- App name bisa diubah via admin panel
- Support email bisa diubah via admin panel
- Perfect untuk white-label solutions

---

## 🎓 Key Takeaway

**Poin Penting:**
1. Gunakan `getSetting()` helper di template email
2. Selalu berikan fallback value untuk robustness
3. Update SEMUA email template (bukan hanya exam-result)
4. Pastikan settings table ter-populate dengan data
5. Test di berbagai email clients (Gmail, Outlook, Apple Mail)

---

**Last Updated:** December 10, 2025  
**Status:** ✅ Ready to Implement

