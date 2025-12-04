# CORS Configuration untuk React JS

## Setup CORS untuk Production

Edit `config/cors.php` sesuai environment:

### Development (Allow All)
```php
'allowed_origins' => ['*'],
```

### Production (Specific Origins Only)

Untuk security yang lebih baik, gunakan specific origins:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],

'allowed_methods' => ['*'],

'allowed_origins' => [
    'http://localhost:3000',           // Local React dev
    'http://localhost:5173',           // Vite local dev
    'https://yourdomain.com',          // Production
    'https://www.yourdomain.com',      // Production www
    'https://app.yourdomain.com',      // App subdomain
],

'allowed_origins_patterns' => [
    '#http://localhost:*#',            // Allow all localhost ports
],

'allowed_headers' => ['*'],

'exposed_headers' => ['X-Total-Count', 'X-Page'],

'max_age' => 86400, // 24 hours

'supports_credentials' => true, // Important for Sanctum auth
```

## Environment Variables

### .env (Laravel)
```
# CORS Configuration
CORS_ALLOWED_ORIGINS=http://localhost:3000,https://yourdomain.com
```

### .env.local (React/Next.js)
```
REACT_APP_API_URL=http://localhost:8000/api
REACT_APP_API_TIMEOUT=5000
```

## Sanctum Configuration (Untuk Authentication)

Edit `config/sanctum.php`:

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,localhost:5173,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ',' . parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

## Axios Configuration Lengkap

### `src/config/axios.js`

```javascript
import axios from 'axios';

// Create axios instance
const api = axios.create({
    baseURL: process.env.REACT_APP_API_URL || 'http://localhost:8000/api',
    timeout: parseInt(process.env.REACT_APP_API_TIMEOUT) || 5000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Add auth token to requests
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Handle responses
api.interceptors.response.use(
    (response) => response,
    (error) => {
        // Handle 401 Unauthorized
        if (error.response?.status === 401) {
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }
        
        // Handle 403 Forbidden
        if (error.response?.status === 403) {
            console.error('Access forbidden');
        }

        return Promise.reject(error);
    }
);

export default api;
```

## Testing CORS

### cURL from Command Line

```bash
# Test preflight request
curl -X OPTIONS http://localhost:8000/api/settings/branding \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: GET" \
  -v

# Test actual request
curl -X GET http://localhost:8000/api/settings/branding \
  -H "Origin: http://localhost:3000" \
  -H "Content-Type: application/json"
```

### Browser Console Test

```javascript
fetch('http://localhost:8000/api/settings/branding', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json'
    },
    credentials: 'include' // Important untuk Sanctum
})
.then(r => r.json())
.then(console.log)
.catch(console.error);
```

## Troubleshooting CORS

### Error: "Access to XMLHttpRequest blocked by CORS policy"

Solusi:
1. Pastikan endpoint ada di allowed_origins
2. Cek method (GET, POST, dll)
3. Cek headers yang dikirim
4. Pastikan `supports_credentials` = true jika pakai auth

### Error 401 Unauthorized pada Auth Request

Solusi:
1. Pastikan token disimpan di localStorage
2. Pastikan format Bearer token benar
3. Check Sanctum configuration

### Preflight Request Failed

Solusi:
1. Tambahkan method ke allowed_methods
2. Tambahkan headers ke allowed_headers
3. Tingkatkan max_age untuk cache preflight

## Security Best Practices

1. ✅ Jangan gunakan `allowed_origins => ['*']` di production
2. ✅ Gunakan HTTPS di production
3. ✅ Set `supports_credentials` = true untuk authentication
4. ✅ Validate semua input di backend
5. ✅ Gunakan rate limiting
6. ✅ Implement proper error handling

## Development Quick Start

### Terminal 1 - Laravel Backend
```bash
cd project/Laravel/vibtech_exam
php artisan serve
# Running on: http://localhost:8000
```

### Terminal 2 - React Frontend
```bash
cd project/react-app
npm start
# Running on: http://localhost:3000
```

### Terminal 3 - Monitor Network (Optional)
```bash
# Windows
netstat -tuln | findstr 8000

# Mac/Linux
lsof -i :8000
```

## Deployment Checklist

- [ ] Update CORS allowed_origins untuk production domain
- [ ] Update React API_URL env variable ke production URL
- [ ] Pastikan HTTPS enabled
- [ ] Configure Sanctum stateful domains
- [ ] Setup rate limiting di API
- [ ] Setup monitoring & logging
- [ ] Test authentication flow
- [ ] Test CORS preflight requests

