// Quick Start - React Settings Implementation

// ============================================
// 1. SETUP SERVICE AXIOS - PALING SIMPLE
// ============================================

import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api'
});

export const getAppBranding = async () => {
    const response = await api.get('/settings/branding');
    return response.data.data; // { name, logo, email }
};

// ============================================
// 2. SIMPLE REACT COMPONENT
// ============================================

import React, { useEffect, useState } from 'react';
import { getAppBranding } from './api';

function App() {
    const [branding, setBranding] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchBranding = async () => {
            try {
                const data = await getAppBranding();
                setBranding(data);
            } catch (error) {
                console.error('Error:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchBranding();
    }, []);

    if (loading) return <div>Loading...</div>;

    return (
        <div>
            {branding?.logo && <img src={branding.logo} alt="Logo" width="100" />}
            <h1>{branding?.name}</h1>
            <p>Support: {branding?.email}</p>
        </div>
    );
}

export default App;

// ============================================
// 3. MENGGUNAKAN REACT QUERY (RECOMMENDED)
// ============================================

import { useQuery } from '@tanstack/react-query';
import axios from 'axios';

const fetchBranding = async () => {
    const { data } = await axios.get('http://localhost:8000/api/settings/branding');
    return data.data;
};

function AppWithReactQuery() {
    const { data: branding, isLoading, error } = useQuery({
        queryKey: ['branding'],
        queryFn: fetchBranding,
        staleTime: 1000 * 60 * 60 // 1 hour cache
    });

    if (isLoading) return <div>Loading...</div>;
    if (error) return <div>Error: {error.message}</div>;

    return (
        <div>
            {branding?.logo && <img src={branding.logo} alt="Logo" />}
            <h1>{branding?.name}</h1>
        </div>
    );
}

// ============================================
// 4. MENGGUNAKAN SWR (SIMPLE WEB FETCHER)
// ============================================

import useSWR from 'swr';

const fetcher = (url) => fetch(url).then(r => r.json()).then(r => r.data);

function AppWithSWR() {
    const { data: branding, error, isLoading } = useSWR(
        'http://localhost:8000/api/settings/branding',
        fetcher
    );

    if (isLoading) return <div>Loading...</div>;
    if (error) return <div>Error loading branding</div>;

    return (
        <div>
            {branding?.logo && <img src={branding.logo} alt="Logo" />}
            <h1>{branding?.name}</h1>
        </div>
    );
}

// ============================================
// 5. COMPONENT DENGAN ERROR HANDLING
// ============================================

import React, { useEffect, useState } from 'react';
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.REACT_APP_API_URL || 'http://localhost:8000/api'
});

function AppHeader() {
    const [branding, setBranding] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        let isMounted = true;

        const loadBranding = async () => {
            try {
                const { data } = await api.get('/settings/branding');
                if (isMounted) {
                    setBranding(data.data);
                    setError(null);
                }
            } catch (err) {
                if (isMounted) {
                    setError(err.message);
                    console.error('Failed to load branding:', err);
                }
            } finally {
                if (isMounted) {
                    setLoading(false);
                }
            }
        };

        loadBranding();

        return () => {
            isMounted = false;
        };
    }, []);

    if (loading) return <header className="loading">Loading header...</header>;
    if (error) return <header className="error">Error loading header</header>;

    return (
        <header className="app-header">
            <div className="header-content">
                {branding?.logo && (
                    <img 
                        src={branding.logo} 
                        alt={branding.name}
                        className="app-logo"
                    />
                )}
                <h1 className="app-name">{branding?.name}</h1>
            </div>
        </header>
    );
}

export default AppHeader;

// ============================================
// 6. TYPESCRIPT VERSION
// ============================================

import React, { useEffect, useState } from 'react';
import axios from 'axios';

interface BrandingData {
    name: string;
    logo: string;
    email: string;
}

interface ApiResponse<T> {
    success: boolean;
    data: T;
    message?: string;
}

const api = axios.create({
    baseURL: process.env.REACT_APP_API_URL || 'http://localhost:8000/api'
});

const getBranding = async (): Promise<BrandingData> => {
    const { data } = await api.get<ApiResponse<BrandingData>>('/settings/branding');
    return data.data;
};

function AppWithTypescript() {
    const [branding, setBranding] = useState<BrandingData | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        getBranding()
            .then(setBranding)
            .catch((err) => setError(err.message))
            .finally(() => setLoading(false));
    }, []);

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div>
            <img src={branding?.logo} alt={branding?.name} />
            <h1>{branding?.name}</h1>
        </div>
    );
}

// ============================================
// 7. CONTEXT API GLOBAL STATE
// ============================================

import React, { createContext, useContext, useEffect, useState, ReactNode } from 'react';

interface BrandingData {
    name: string;
    logo: string;
    email: string;
}

interface BrandingContextType {
    branding: BrandingData | null;
    loading: boolean;
    error: string | null;
}

const BrandingContext = createContext<BrandingContextType | undefined>(undefined);

export function BrandingProvider({ children }: { children: ReactNode }) {
    const [branding, setBranding] = useState<BrandingData | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchBranding = async () => {
            try {
                const response = await fetch('http://localhost:8000/api/settings/branding');
                const json = await response.json();
                setBranding(json.data);
            } catch (err) {
                setError(err instanceof Error ? err.message : 'Unknown error');
            } finally {
                setLoading(false);
            }
        };

        fetchBranding();
    }, []);

    return (
        <BrandingContext.Provider value={{ branding, loading, error }}>
            {children}
        </BrandingContext.Provider>
    );
}

export function useBranding() {
    const context = useContext(BrandingContext);
    if (!context) {
        throw new Error('useBranding must be used within BrandingProvider');
    }
    return context;
}

// Usage:
function AppWithContext() {
    const { branding, loading, error } = useBranding();

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div>
            <img src={branding?.logo} alt="Logo" />
            <h1>{branding?.name}</h1>
        </div>
    );
}

// ============================================
// 8. NEXT.JS 14 SERVER COMPONENT
// ============================================

// app/layout.tsx
async function getAppBranding() {
    const res = await fetch('http://localhost:8000/api/settings/branding', {
        next: { revalidate: 3600 } // Cache for 1 hour
    });

    if (!res.ok) {
        return null;
    }

    const data = await res.json();
    return data.data;
}

export default async function RootLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const branding = await getAppBranding();

    return (
        <html lang="en">
            <body>
                <header>
                    {branding?.logo && <img src={branding.logo} alt="Logo" />}
                    <h1>{branding?.name}</h1>
                </header>
                {children}
            </body>
        </html>
    );
}

// ============================================
// 9. FETCH DATA DENGAN LOADER (React Router)
// ============================================

import { useLoaderData } from 'react-router-dom';

export async function brandinLoader() {
    const response = await fetch('http://localhost:8000/api/settings/branding');
    return response.json();
}

function Page() {
    const { data } = useLoaderData() as { data: BrandingData };

    return (
        <div>
            <img src={data.logo} alt={data.name} />
            <h1>{data.name}</h1>
        </div>
    );
}

// ============================================
// 10. OPTIMIZED WITH INTERCEPTORS
// ============================================

import axios from 'axios';

const api = axios.create({
    baseURL: process.env.REACT_APP_API_URL || 'http://localhost:8000/api',
    timeout: 5000
});

// Add error interceptor
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 404) {
            console.error('Settings not found');
        }
        return Promise.reject(error);
    }
);

export const settingsAPI = {
    getBranding: () => api.get('/settings/branding').then(r => r.data.data),
    getLogo: () => api.get('/settings/logo').then(r => r.data.data.logo),
    getAppName: () => api.get('/settings/app-name').then(r => r.data.data.name),
};

// ============================================
// SUMMARY - PILIH SALAH SATU
// ============================================

/*
UNTUK PEMULA: Gunakan #1 + #2
UNTUK BEST PRACTICE: Gunakan #5 + Context API (#7)
UNTUK PERFORMANCE: Gunakan #3 (React Query)
UNTUK NEXT.JS: Gunakan #8 (Server Components)
UNTUK TYPESCRIPT: Gunakan #6
*/
