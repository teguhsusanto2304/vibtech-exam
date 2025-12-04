<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Show the settings page
     */
    public function index()
    {
        $appName = Setting::getValue('app_name', config('app.name'));
        $appLogo = Setting::getValue('app_logo', '/images/logo.png');
        $supportEmail = Setting::getValue('support_email', env('MAIL_FROM_ADDRESS'));

        return view('admin.settings', compact(
            'appName',
            'appLogo',
            'supportEmail'
        ));
    }

    /**
     * Update the settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'support_email' => 'required|email|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        // Update app name
        Setting::setValue('app_name', $validated['app_name']);

        // Update support email
        Setting::setValue('support_email', $validated['support_email']);

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            $file = $request->file('app_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store the file in public/images directory
            $path = $file->storeAs('images', $filename, 'public');
            
            // Save the path to settings
            Setting::setValue('app_logo', '/storage/' . $path);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully!');
    }
}
