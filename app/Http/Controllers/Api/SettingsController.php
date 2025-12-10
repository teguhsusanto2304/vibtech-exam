<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * Get all application settings
     * 
     * @return JsonResponse
     */
    public function getSettings(): JsonResponse
    {
        try {
            $settings = [
                'app_name' => getSetting('app_name', config('app.name')),
                'app_logo' => getSetting('app_logo', '/images/logo.png'),
                'support_email' => getSetting('support_email', env('MAIL_FROM_ADDRESS')),
            ];

            return response()->json([
                'success' => true,
                'data' => $settings,
                'message' => 'Settings retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific setting by key
     * 
     * @param string $key
     * @return JsonResponse
     */
    public function getSetting($key): JsonResponse
    {
        try {
            // Validate key
            $allowedKeys = ['app_name', 'app_logo', 'support_email'];
            
            if (!in_array($key, $allowedKeys)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid setting key'
                ], 400);
            }

            $value = getSetting($key);

            return response()->json([
                'success' => true,
                'data' => [
                    'key' => $key,
                    'value' => $value
                ],
                'message' => 'Setting retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve setting',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get branding info (public endpoint - no auth required)
     * 
     * @return JsonResponse
     */
    public function getBranding(): JsonResponse
    {
        try {
            $branding = [
                'name' => getSetting('app_name', config('app.name')),
                'logo' => getSetting('app_logo', '/images/logo.png'),
                'email' => getSetting('support_email', env('MAIL_FROM_ADDRESS')),
            ];

            return response()->json([
                'success' => true,
                'data' => $branding
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve branding information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get logo only
     * 
     * @return JsonResponse
     */
    public function getLogo(): JsonResponse
    {
        try {
            $logo = getSetting('app_logo', '/images/logo.png');
            $appName = getSetting('app_name', config('app.name'));
            return response()->json([
                'success' => true,
                'data' => [
                    'logo' => $logo,
                    'appName'=> $appName
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve logo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get app name only
     * 
     * @return JsonResponse
     */
    public function getAppName(): JsonResponse
    {
        try {
            $appName = getSetting('app_name', config('app.name'));

            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $appName
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve app name',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
