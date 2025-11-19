<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Definisikan Origins yang Diizinkan
        $allowedOrigins = [
            'http://192.168.100.46:8000', 
            'http://192.168.100.46:3000',
            // Tambahkan origin lain yang dibutuhkan di sini
        ];
        
        // 2. Definisikan Header dan Metode CORS
        // Metode harus mencakup POST, PUT, DELETE, dll.
        $allowedMethods = 'GET, POST, PUT, DELETE, OPTIONS';
        
        // Header harus mencakup Content-Type dan header kustom yang Anda kirimkan (seperti Authorization)
        $allowedHeaders = 'Content-Type, Authorization, X-Requested-With, X-Auth-Token, Origin';
        
        // Dapatkan origin dari request
        $requestOrigin = $request->header('Origin');
        
        // Cek apakah origin request termasuk yang diizinkan
        $isOriginAllowed = in_array($requestOrigin, $allowedOrigins);
        
        // Ambil Response (baik dari $next atau Response OPTIONS)
        $response = null;

        // --- TANGANI PREFLIGHT REQUEST (OPTIONS) ---
        if ($request->isMethod('OPTIONS')) {
            // Jika Origin diizinkan, kirimkan respons 204 No Content dengan Header CORS
            if ($isOriginAllowed) {
                return response()
                    ->make('', 204) 
                    ->header('Access-Control-Allow-Origin', $requestOrigin)
                    ->header('Access-Control-Allow-Methods', $allowedMethods)
                    ->header('Access-Control-Allow-Headers', $allowedHeaders)
                    ->header('Access-Control-Allow-Credentials', 'true')
                    // Opsional: Atur cache untuk preflight request (max-age)
                    ->header('Access-Control-Max-Age', '86400'); 
            }
            
            // Jika Origin TIDAK diizinkan, kirimkan respons 403 Forbidden atau 204 kosong (lebih aman)
            return response()->make('', 403); 
        }

        // --- TANGANI REQUEST NORMAL (GET, POST, dll.) ---
        
        // Biarkan request berjalan melalui stack Laravel
        $response = $next($request);

        // Tambahkan header CORS ke response normal jika Origin diizinkan
        if ($isOriginAllowed) {
            $response
                ->header('Access-Control-Allow-Origin', $requestOrigin)
                ->header('Access-Control-Allow-Methods', $allowedMethods)
                ->header('Access-Control-Allow-Headers', $allowedHeaders)
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}