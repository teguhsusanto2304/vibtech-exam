# API Settings Integration - Complete Guide

## 📋 Overview

Sistem API telah dibuat untuk share logo dan nama aplikasi dengan React JS. Berikut adalah ringkasan lengkapnya:

## 🚀 Quick Start (5 Menit)

### 1. Backend Setup (Laravel) - SUDAH SELESAI

**File yang dibuat:**
- ✅ `app/Http/Controllers/Api/SettingsController.php` - API Controller
- ✅ `routes/api.php` - API Routes (Updated)
- ✅ `app/Models/Setting.php` - Model (Sudah ada)
- ✅ Migration untuk settings table

**Endpoints tersedia:**
```
GET /api/settings/branding      (Public - No Auth)
GET /api/settings/logo          (Public - No Auth)
GET /api/settings/app-name      (Public - No Auth)
GET /api/settings               (Protected - With Auth)
GET /api/settings/{key}         (Protected - With Auth)
```

### 2. Frontend Setup (React) - Copy Code Ini

**Langkah 1: Install Axios (jika belum ada)**
```bash
npm install axios
```

**Langkah 2: Buat file `src/services/settingsService.js`**
```javascript
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.REACT_APP_API_URL || 'http://localhost:8000/api'
});

export const getAppBranding = async () => {
    const response = await api.get('/settings/branding');
    return response.data.data; // { name, logo, email }
};

export const getLogo = async () => {
    const response = await api.get('/settings/logo');
    return response.data.data.logo;
};

export const getAppName = async () => {
    const response = await api.get('/settings/app-name');
    return response.data.data.name;
};
```

**Langkah 3: Update `src/App.jsx`**
```javascript
import { useEffect, useState } from 'react';
import { getAppBranding } from './services/settingsService';

function App() {
    const [branding, setBranding] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        getAppBranding()
            .then(setBranding)
            .catch(console.error)
            .finally(() => setLoading(false));
    }, []);

    if (loading) return <div>Loading...</div>;

    return (
        <div>
            {branding?.logo && (
                <img src={branding.logo} alt={branding.name} width="100" />
            )}
            <h1>{branding?.name}</h1>
            <p>Support: {branding?.email}</p>
        </div>
    );
}

export default App;
```

**Langkah 4: Update `.env`**
```
REACT_APP_API_URL=http://localhost:8000/api
```

**Langkah 5: Run React**
```bash
npm start
```

## 📊 API Response Examples

### Branding Endpoint
```bash
GET /api/settings/branding
```

**Response:**
```json
{
    "success": true,
    "data": {
        "name": "Vibtech Exam",
        "logo": "/storage/images/logo_123456.png",
        "email": "support@vibtech.com"
    }
}
```

### Logo Only Endpoint
```bash
GET /api/settings/logo
```

**Response:**
```json
{
    "success": true,
    "data": {
        "logo": "/storage/images/logo_123456.png"
    }
}
```

### App Name Endpoint
```bash
GET /api/settings/app-name
```

**Response:**
```json
{
    "success": true,
    "data": {
        "name": "Vibtech Exam"
    }
}
```

## 🔐 Protected Endpoints (Dengan Authentication)

```bash
# Get All Settings (Perlu Token Sanctum)
curl -X GET http://localhost:8000/api/settings \
  -H "Authorization: Bearer YOUR_TOKEN"

# Get Specific Setting
curl -X GET http://localhost:8000/api/settings/app_name \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🏗️ Architecture Diagram

```
┌─────────────────────────────────────┐
│      React JS Application           │
│  (http://localhost:3000)            │
│                                     │
│  ┌──────────────────────────────┐  │
│  │  Header Component            │  │
│  │  - Display Logo from API     │  │
│  │  - Display App Name from API │  │
│  │  - Use Support Email from API│  │
│  └──────────────────────────────┘  │
└────────────────┬────────────────────┘
                 │
                 │ HTTP Request
                 │ (CORS Enabled)
                 ▼
┌─────────────────────────────────────┐
│    Laravel Backend                  │
│  (http://localhost:8000)            │
│                                     │
│  ┌──────────────────────────────┐  │
│  │  API Routes (/api/settings)  │  │
│  └──────────────────────────────┘  │
│                │                    │
│                ▼                    │
│  ┌──────────────────────────────┐  │
│  │  SettingsController          │  │
│  │  - getBranding()             │  │
│  │  - getLogo()                 │  │
│  │  - getAppName()              │  │
│  └──────────────────────────────┘  │
│                │                    │
│                ▼                    │
│  ┌──────────────────────────────┐  │
│  │  Setting Model               │  │
│  │  - getValue()                │  │
│  │  - setSetting()              │  │
│  └──────────────────────────────┘  │
│                │                    │
│                ▼                    │
│  ┌──────────────────────────────┐  │
│  │  Settings Table (Database)   │  │
│  │  - key (PK): app_name        │  │
│  │  - key: app_logo             │  │
│  │  - key: support_email        │  │
│  └──────────────────────────────┘  │
└─────────────────────────────────────┘
```

## 📁 File Structure

```
Laravel Project
├── app/
│   ├── Http/Controllers/
│   │   └── Api/
│   │       └── SettingsController.php     ← NEW
│   ├── Models/
│   │   └── Setting.php                    ← NEW
│   └── Helpers/
│       └── SettingsHelper.php             ← EXISTING
├── database/
│   └── migrations/
│       └── 2025_12_03_000001_create_settings_table.php ← NEW
├── routes/
│   └── api.php                            ← UPDATED
└── config/
    └── cors.php                           ← CONFIGURED

React Project
├── src/
│   ├── services/
│   │   └── settingsService.js            ← CREATE
│   ├── components/
│   │   ├── Header.jsx                    ← USE API
│   │   └── Footer.jsx                    ← USE API
│   ├── App.jsx                           ← UPDATE
│   └── .env                              ← CREATE
```

## 🔧 Environment Variables

### Laravel (.env)
```
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vibtech_exam
DB_USERNAME=root
DB_PASSWORD=

# App
APP_NAME="Vibtech Exam"
APP_URL=http://localhost:8000

# CORS (Optional untuk production)
CORS_ALLOWED_ORIGINS=http://localhost:3000
```

### React (.env)
```
REACT_APP_API_URL=http://localhost:8000/api
REACT_APP_API_TIMEOUT=5000
```

## ✅ Testing Endpoints

### 1. Test dengan cURL

```bash
# Test Branding
curl http://localhost:8000/api/settings/branding

# Test Logo
curl http://localhost:8000/api/settings/logo

# Test App Name
curl http://localhost:8000/api/settings/app-name
```

### 2. Test dengan Postman

1. Open Postman
2. Create New Request
3. Method: GET
4. URL: `http://localhost:8000/api/settings/branding`
5. Send

### 3. Test dengan React

```javascript
// Di browser console
fetch('http://localhost:8000/api/settings/branding')
  .then(r => r.json())
  .then(console.log)
```

## 🐛 Common Issues & Solutions

### Issue 1: CORS Error
```
Access to XMLHttpRequest from origin 'http://localhost:3000' 
has been blocked by CORS policy
```

**Solution:**
- Pastikan `config/cors.php` sudah benar
- Pastikan endpoint ada di allowed_origins
- Restart Laravel server: `php artisan serve`

### Issue 2: 404 Not Found

**Solution:**
- Pastikan route sudah didaftarkan di `routes/api.php`
- Pastikan SettingsController ada di `app/Http/Controllers/Api/`
- Jalankan: `php artisan route:list` untuk check routes

### Issue 3: Settings Table Not Found

**Solution:**
```bash
# Run migration
php artisan migrate

# Seed default settings (optional)
php artisan tinker
>>> App\Models\Setting::setValue('app_name', 'Vibtech Exam');
>>> App\Models\Setting::setValue('app_logo', '/images/logo.png');
>>> App\Models\Setting::setValue('support_email', 'support@vibtech.com');
```

## 🚀 Advanced Features

### 1. Caching Settings (Performance)

```php
// Di SettingsController
use Illuminate\Support\Facades\Cache;

public function getBranding()
{
    return Cache::remember('branding', 3600, function () {
        return [
            'name' => getSetting('app_name'),
            'logo' => getSetting('app_logo'),
            'email' => getSetting('support_email'),
        ];
    });
}
```

### 2. React Query for Better UX

```javascript
import { useQuery } from '@tanstack/react-query';

const { data: branding } = useQuery({
    queryKey: ['branding'],
    queryFn: getAppBranding,
    staleTime: 1000 * 60 * 60, // 1 hour
    gcTime: 1000 * 60 * 60 * 24, // 24 hours
});
```

### 3. Real-time Updates dengan WebSocket

```javascript
// Listen untuk perubahan settings
useEffect(() => {
    const channel = window.Echo.channel('settings-updated')
        .listen('SettingsUpdated', () => {
            // Refetch branding
            queryClient.invalidateQueries({ queryKey: ['branding'] });
        });

    return () => channel.stopListening();
}, []);
```

## 📚 Additional Resources

- **API Documentation**: Lihat `API_SETTINGS_DOCUMENTATION.md`
- **React Integration Examples**: Lihat `REACT_INTEGRATION_EXAMPLES.md`
- **CORS Setup**: Lihat `CORS_SETUP_GUIDE.md`
- **Settings Config**: Lihat `SETTINGS_CONFIGURATION.md`

## 🎯 Next Steps

1. ✅ Run Laravel migrations: `php artisan migrate`
2. ✅ Setup React project dengan axios
3. ✅ Test endpoints dengan cURL atau Postman
4. ✅ Integrate ke React components
5. ✅ Test di browser
6. ✅ Setup production CORS configuration
7. ✅ Deploy!

## 📞 Support

Jika ada pertanyaan atau error:

1. Check documentation files
2. Test endpoint dengan cURL
3. Check Laravel logs: `storage/logs/`
4. Check browser console untuk React errors
5. Run: `php artisan route:list` untuk verify routes

---

**Status**: ✅ API Implementation Complete  
**Last Updated**: December 3, 2025  
**Version**: 1.0.0

