# API Documentation - Application Settings

## Endpoints Overview

### Public Endpoints (No Authentication Required)

#### 1. Get Branding Information
**GET** `/api/settings/branding`

Mengambil semua informasi branding aplikasi (nama, logo, dan email support).

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

#### 2. Get Logo Only
**GET** `/api/settings/logo`

Mengambil hanya logo aplikasi.

**Response:**
```json
{
    "success": true,
    "data": {
        "logo": "/storage/images/logo_123456.png"
    }
}
```

#### 3. Get App Name Only
**GET** `/api/settings/app-name`

Mengambil hanya nama aplikasi.

**Response:**
```json
{
    "success": true,
    "data": {
        "name": "Vibtech Exam"
    }
}
```

---

### Protected Endpoints (Authentication Required)

#### 4. Get All Settings
**GET** `/api/settings`

Mengambil semua settings (memerlukan Sanctum token).

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "app_name": "Vibtech Exam",
        "app_logo": "/storage/images/logo_123456.png",
        "support_email": "support@vibtech.com"
    },
    "message": "Settings retrieved successfully"
}
```

#### 5. Get Specific Setting
**GET** `/api/settings/{key}`

Mengambil setting spesifik berdasarkan key.

**Parameters:**
- `key` (required): `app_name`, `app_logo`, atau `support_email`

**Response:**
```json
{
    "success": true,
    "data": {
        "key": "app_name",
        "value": "Vibtech Exam"
    },
    "message": "Setting retrieved successfully"
}
```

---

## React JS Integration Examples

### 1. Setup Service dengan Axios

**File: `src/services/settingsService.js`**

```javascript
import axios from 'axios';

const API_BASE = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';

// Instance untuk public endpoints (tanpa auth)
const publicAPI = axios.create({
    baseURL: API_BASE
});

// Instance untuk protected endpoints (dengan auth)
const authenticatedAPI = axios.create({
    baseURL: API_BASE
});

// Interceptor untuk menambahkan token
authenticatedAPI.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Settings Service
const settingsService = {
    // Public endpoints
    getBranding: async () => {
        try {
            const response = await publicAPI.get('/settings/branding');
            return response.data.data;
        } catch (error) {
            console.error('Error fetching branding:', error);
            throw error;
        }
    },

    getLogo: async () => {
        try {
            const response = await publicAPI.get('/settings/logo');
            return response.data.data.logo;
        } catch (error) {
            console.error('Error fetching logo:', error);
            throw error;
        }
    },

    getAppName: async () => {
        try {
            const response = await publicAPI.get('/settings/app-name');
            return response.data.data.name;
        } catch (error) {
            console.error('Error fetching app name:', error);
            throw error;
        }
    },

    // Protected endpoints
    getAllSettings: async () => {
        try {
            const response = await authenticatedAPI.get('/settings');
            return response.data.data;
        } catch (error) {
            console.error('Error fetching all settings:', error);
            throw error;
        }
    },

    getSetting: async (key) => {
        try {
            const response = await authenticatedAPI.get(`/settings/${key}`);
            return response.data.data.value;
        } catch (error) {
            console.error(`Error fetching setting ${key}:`, error);
            throw error;
        }
    }
};

export default settingsService;
```

### 2. React Hook Custom Hook

**File: `src/hooks/useSettings.js`**

```javascript
import { useState, useEffect } from 'react';
import settingsService from '../services/settingsService';

export const useSettings = () => {
    const [settings, setSettings] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchSettings = async () => {
            try {
                setLoading(true);
                const data = await settingsService.getBranding();
                setSettings(data);
                setError(null);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchSettings();
    }, []);

    return { settings, loading, error };
};
```

### 3. Component Header dengan Logo

**File: `src/components/Header.jsx`**

```javascript
import React from 'react';
import { useSettings } from '../hooks/useSettings';

const Header = () => {
    const { settings, loading, error } = useSettings();

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <header className="header">
            <div className="header-container">
                <div className="logo-section">
                    {settings?.logo && (
                        <img 
                            src={settings.logo} 
                            alt={settings.name} 
                            className="logo"
                        />
                    )}
                    <h1>{settings?.name}</h1>
                </div>
            </div>
        </header>
    );
};

export default Header;
```

### 4. Component Footer dengan Email Support

**File: `src/components/Footer.jsx`**

```javascript
import React from 'react';
import { useSettings } from '../hooks/useSettings';

const Footer = () => {
    const { settings, loading } = useSettings();

    return (
        <footer className="footer">
            <div className="footer-content">
                <p>&copy; 2025 {settings?.name}. All rights reserved.</p>
                <p>
                    Support: <a href={`mailto:${settings?.email}`}>
                        {settings?.email}
                    </a>
                </p>
            </div>
        </footer>
    );
};

export default Footer;
```

### 5. Global Settings Provider (Context API)

**File: `src/context/SettingsContext.js`**

```javascript
import React, { createContext, useContext, useState, useEffect } from 'react';
import settingsService from '../services/settingsService';

const SettingsContext = createContext();

export const SettingsProvider = ({ children }) => {
    const [settings, setSettings] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchSettings = async () => {
            try {
                setLoading(true);
                const data = await settingsService.getBranding();
                setSettings(data);
                setError(null);
            } catch (err) {
                setError(err.message);
                console.error('Failed to load settings:', err);
            } finally {
                setLoading(false);
            }
        };

        fetchSettings();
    }, []);

    return (
        <SettingsContext.Provider value={{ settings, loading, error }}>
            {children}
        </SettingsContext.Provider>
    );
};

export const useSettings = () => {
    const context = useContext(SettingsContext);
    if (!context) {
        throw new Error('useSettings must be used within SettingsProvider');
    }
    return context;
};
```

### 6. Penggunaan dalam App.jsx

**File: `src/App.jsx`**

```javascript
import React from 'react';
import { SettingsProvider } from './context/SettingsContext';
import Header from './components/Header';
import Footer from './components/Footer';
import MainContent from './pages/MainContent';

function App() {
    return (
        <SettingsProvider>
            <div className="app">
                <Header />
                <main>
                    <MainContent />
                </main>
                <Footer />
            </div>
        </SettingsProvider>
    );
}

export default App;
```

### 7. Fetch Langsung dalam Component

**File: `src/components/BrandingCard.jsx`**

```javascript
import React, { useEffect, useState } from 'react';
import settingsService from '../services/settingsService';

const BrandingCard = () => {
    const [appName, setAppName] = useState('');
    const [logo, setLogo] = useState('');
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const loadSettings = async () => {
            try {
                const name = await settingsService.getAppName();
                const logoUrl = await settingsService.getLogo();
                
                setAppName(name);
                setLogo(logoUrl);
            } catch (error) {
                console.error('Failed to load branding:', error);
            } finally {
                setLoading(false);
            }
        };

        loadSettings();
    }, []);

    if (loading) return <div>Loading branding...</div>;

    return (
        <div className="branding-card">
            <img src={logo} alt={appName} className="branding-logo" />
            <h2>{appName}</h2>
        </div>
    );
};

export default BrandingCard;
```

---

## Environment Variables

**File: `.env` (React)**

```
REACT_APP_API_URL=http://localhost:8000/api
REACT_APP_APP_NAME=Vibtech Exam
```

---

## Testing dengan cURL

```bash
# Get Branding
curl -X GET http://localhost:8000/api/settings/branding

# Get Logo
curl -X GET http://localhost:8000/api/settings/logo

# Get App Name
curl -X GET http://localhost:8000/api/settings/app-name

# Get All Settings (dengan token)
curl -X GET http://localhost:8000/api/settings \
  -H "Authorization: Bearer YOUR_TOKEN"

# Get Specific Setting (dengan token)
curl -X GET http://localhost:8000/api/settings/app_name \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Error Handling

Semua endpoint mengembalikan response dengan struktur yang konsisten:

### Success Response
```json
{
    "success": true,
    "data": {...},
    "message": "Success message"
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "error": "Detailed error"
}
```

---

## Features

✅ Public endpoints untuk branding (tanpa auth)  
✅ Protected endpoints untuk admin (dengan auth)  
✅ Error handling yang baik  
✅ Response structure yang konsisten  
✅ Mudah diintegrasikan dengan React  
✅ Caching optimization ready  
✅ CORS support (sesuaikan di config/cors.php)  

