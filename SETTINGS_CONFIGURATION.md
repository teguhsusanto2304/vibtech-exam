# Application Settings Configuration

This document describes the new Settings Configuration system added to the admin panel.

## Overview

The Settings Configuration page allows administrators to manage core application settings including:
- Application Name
- Support Email
- Application Logo

## Files Created/Modified

### New Files

1. **Migration**: `database/migrations/2025_12_03_000001_create_settings_table.php`
   - Creates the `settings` table with `key`, `value`, and timestamps

2. **Model**: `app/Models/Setting.php`
   - Handles database operations for settings
   - Provides helper methods: `getValue()`, `setValue()`, `getAll()`

3. **Controller**: `app/Http/Controllers/SettingsController.php`
   - `index()` - Display settings page
   - `update()` - Handle form submission and file uploads

4. **View**: `resources/views/admin/settings.blade.php`
   - User interface for managing settings
   - Form validation and feedback

5. **Helper**: `app/Helpers/SettingsHelper.php`
   - Global helper functions for easy access to settings
   - Functions: `getSetting()`, `appName()`, `appLogo()`, `supportEmail()`

### Modified Files

1. **routes/web.php**
   - Added settings routes
   - Added SettingsController import

2. **composer.json**
   - Added SettingsHelper to autoload files

3. **resources/views/layouts/admin/sidebar.blade.php**
   - Added Settings link to admin sidebar

## How to Use

### Access Settings Page

Navigate to `/admin/settings` or click "Settings" in the admin sidebar.

### Using Settings in Views

#### Option 1: Using Helper Functions
```blade
<h1>{{ appName() }}</h1>
<img src="{{ appLogo() }}" alt="Logo">
<p>Contact: {{ supportEmail() }}</p>
```

#### Option 2: Using getSetting() Function
```blade
{{ getSetting('app_name', 'Default Name') }}
{{ getSetting('app_logo') }}
{{ getSetting('support_email') }}
```

#### Option 3: Using Setting Model
```php
$appName = Setting::getValue('app_name');
Setting::setValue('app_name', 'New Name');
$allSettings = Setting::getAll();
```

### Database Usage in Controllers

```php
use App\Models\Setting;

$appName = Setting::getValue('app_name', 'Default');
Setting::setValue('support_email', 'new@example.com');
```

## Features

### 1. Application Name
- Text input field
- Stored in database and displayed throughout the application
- Max 255 characters

### 2. Support Email
- Email validation
- Used for support communications
- Max 255 characters

### 3. Application Logo
- Accepts JPEG, PNG, JPG, GIF formats
- Maximum file size: 5MB
- Stored in `storage/app/public/images/`
- Previous logo preview shown on form
- Auto-generates unique filename with timestamp

## File Upload Configuration

The logo upload uses Laravel's storage system:
- **Disk**: `public` (configured in `config/filesystems.php`)
- **Path**: `images/`
- **URL Access**: `/storage/images/filename.ext`

Make sure the storage symlink is created:
```bash
php artisan storage:link
```

## Form Validation

All validations are performed server-side:

| Field | Validation |
|-------|-----------|
| app_name | required, string, max:255 |
| support_email | required, email, max:255 |
| app_logo | nullable, image, mimes:jpeg,png,jpg,gif, max:5120 |

## UI/UX Features

- **Responsive Design**: Works on desktop and mobile devices
- **Dark Mode Support**: Full dark mode compatibility
- **Real-time Feedback**: Success and error messages
- **Current Logo Preview**: Shows thumbnail of current logo
- **Information Card**: Helpful tips about settings

## Security

- CSRF token required for form submission
- File type validation on upload
- File size limits enforced
- Email validation for support email
- Proper authorization (admin only)

## Database Schema

```sql
CREATE TABLE settings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    key VARCHAR(255) UNIQUE NOT NULL,
    value LONGTEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Example Settings in Database

```
| id | key | value | created_at | updated_at |
|----|-----|-------|------------|------------|
| 1 | app_name | Vibtech Exam | ... | ... |
| 2 | support_email | support@vibtech.com | ... | ... |
| 3 | app_logo | /storage/images/logo_123456.png | ... | ... |
```

## Migration and Setup

Run the migration to create the settings table:

```bash
php artisan migrate
```

Optionally seed default settings:

```php
use App\Models\Setting;

Setting::setValue('app_name', 'Vibtech Exam');
Setting::setValue('support_email', env('MAIL_FROM_ADDRESS'));
```

## Available Route Names

- `admin.settings` - GET: Display settings form
- `admin.settings.update` - PUT: Update settings

## Troubleshooting

### Logo not showing after upload
- Check if storage symlink exists: `php artisan storage:link`
- Verify file permissions on `storage/app/public/images/`
- Check disk configuration in `config/filesystems.php`

### Helper functions not loading
- Run `composer dump-autoload`
- Verify `composer.json` autoload configuration includes the helper file

### Settings not persisting
- Check database connection
- Verify settings table exists: `php artisan migrate`
- Check if Setting model is correctly imported

## Future Enhancements

- Add more configurable settings (theme colors, app description, etc.)
- Implement settings cache for performance
- Add settings export/import functionality
- Add email template customization
- Add application branding customization (favicon, etc.)
