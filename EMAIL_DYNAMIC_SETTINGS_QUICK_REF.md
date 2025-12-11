# 🚀 QUICK REFERENCE: Dynamic Settings di Email Template

## ✅ Yang Sudah Diupdate

### File: `resources/views/emails/exam-result.blade.php`

**Perubahan:**
- ✅ Logo dari settings table (bukan hardcoded)
- ✅ App name dari settings table
- ✅ Support email dari settings table
- ✅ Professional layout dengan styling
- ✅ Conditional rendering untuk passed/failed

---

## 📝 3 Baris Kode Penting

```blade
@php
    $appLogo = getSetting('app_logo', '/images/logo.png');
    $appName = getSetting('app_name', config('app.name'));
    $supportEmail = getSetting('support_email', config('mail.from.address'));
@endphp
```

**Penjelasan:**
1. `getSetting()` - Ambil dari table settings
2. Parameter kedua - Fallback value jika tidak ada di settings

---

## 📧 Rendering Logo

```blade
<div style="text-align: center; margin-bottom: 30px;">
    @if($appLogo && $appLogo !== '/images/logo.png')
        <!-- Custom logo dari settings -->
        <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 200px;">
    @else
        <!-- Fallback: render nama app sebagai text -->
        <div style="font-size: 24px; font-weight: bold; color: #005A9C;">
            {{ $appName }}
        </div>
    @endif
</div>
```

---

## 🎯 Langkah Implementasi (5 Menit)

### Step 1: Copy-Paste Code ✅
Kode sudah di-update di `resources/views/emails/exam-result.blade.php`

### Step 2: Verify Settings Helper ✅
```php
// Pastikan di composer.json:
"autoload": {
    "files": [
        "app/Helpers/SettingsHelper.php"
    ]
}

// Run:
composer dump-autoload
```

### Step 3: Fill Settings Data ✅
**Via Admin Panel:**
- Go to: `/admin/settings`
- Upload logo
- Set app name
- Set support email

**Atau via Tinker:**
```php
php artisan tinker
>>> App\Models\Setting::setValue('app_logo', '/storage/images/logo.png');
>>> App\Models\Setting::setValue('app_name', 'Vibtech Exam');
>>> App\Models\Setting::setValue('support_email', 'support@vibtech.com');
```

### Step 4: Test Email Sending ✅
```php
php artisan tinker
>>> $student = App\Models\User::first();
>>> $result = App\Models\UserExam::with('exam')->first();
>>> Mail::send(new \App\Mail\ExamResultMail($student, $result, 'passed'));
```

### Step 5: Verify Logo di Email ✅
Check inbox → Email harus show logo dari settings

---

## 🔄 Pattern untuk Email Template Lain

Gunakan pattern yang sama untuk semua email template:

```blade
<!-- Step 1: Get settings -->
@php
    $appLogo = getSetting('app_logo');
    $appName = getSetting('app_name', config('app.name'));
    $supportEmail = getSetting('support_email', config('mail.from.address'));
@endphp

<!-- Step 2: Render logo di header -->
<div style="text-align: center; margin-bottom: 30px;">
    @if($appLogo)
        <img src="{{ $appLogo }}" alt="{{ $appName }}" style="max-width: 150px;">
    @else
        <h2 style="color: #005A9C;">{{ $appName }}</h2>
    @endif
</div>

<!-- Step 3: Gunakan di footer -->
Best regards,<br>
{{ $appName }}<br>
{{ $supportEmail }}
```

---

## 📊 Template yang Perlu Diupdate

| Template | File | Status |
|----------|------|--------|
| Exam Result | `emails/exam-result.blade.php` | ✅ DONE |
| Welcome | `emails/welcome.blade.php` | 📝 See examples |
| Exam Assigned | `emails/exam-assigned.blade.php` | 📝 See examples |
| Exam Reminder | `emails/exam-reminder.blade.php` | 📝 See examples |
| Password Reset | `emails/password-reset.blade.php` | 📝 See examples |
| Admin Notification | `emails/admin-exam-status.blade.php` | 📝 See examples |

---

## 🎓 Benefits

### ❌ BEFORE (Hardcoded Logo)
```blade
<!-- Logo hardcoded di template -->
<img src="/images/logo.png" alt="Logo">

<!-- App name hardcoded dari config -->
{{ config('app.name') }}
```

**Problem:**
- Harus edit file untuk ganti logo
- Harus edit .env untuk ganti app name
- Tidak bisa white-label dengan mudah

### ✅ AFTER (Dynamic from Settings)
```blade
<!-- Logo dari database -->
@php $appLogo = getSetting('app_logo'); @endphp
<img src="{{ $appLogo }}" alt="Logo">

<!-- App name dari database -->
@php $appName = getSetting('app_name'); @endphp
{{ $appName }}
```

**Benefits:**
- Ubah logo via admin panel, email langsung update
- Ubah app name via admin panel, email langsung update
- Perfect untuk multi-tenant & white-label
- User-friendly admin experience

---

## 🔐 Security Notes

1. **Logo URL Validation**
   ```php
   // Pastikan logo URL safe
   @if($appLogo && $appLogo !== '/images/logo.png')
       <img src="{{ $appLogo }}" alt="...">
   @endif
   ```

2. **Email Content Escaping**
   ```blade
   <!-- Blade auto-escapes untuk prevent XSS -->
   {{ $appName }} <!-- Safe -->
   {!! $appName !!} <!-- Only jika trusted -->
   ```

3. **Fallback Values**
   ```php
   // Selalu berikan fallback
   getSetting('app_logo', '/images/default-logo.png')
   ```

---

## 🧪 Email Testing Tools

### 1. Mailtrap (Recommended)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
```

### 2. Mailhog (Local Development)
```bash
# Install Mailhog (macOS)
brew install mailhog

# Run Mailhog
mailhog

# Access: http://localhost:1025 (API), http://localhost:8025 (UI)
```

### 3. Laravel Logs
```php
// Di .env
MAIL_MAILER=log

// Emails akan logged di: storage/logs/laravel.log
```

---

## 📋 Checklist

- [ ] Read email template di `resources/views/emails/exam-result.blade.php`
- [ ] Verify getSetting() helper tersedia
- [ ] Run `composer dump-autoload`
- [ ] Fill settings via admin panel
- [ ] Test email sending via Tinker
- [ ] Verify logo muncul di Gmail/Outlook/Apple Mail
- [ ] Update other email templates (using same pattern)
- [ ] Test fallback (jika logo tidak ada)
- [ ] Set MAIL_MAILER di .env sesuai production

---

## 🎉 Done!

Template email sudah dynamic dan bisa customize via admin panel! 🚀

