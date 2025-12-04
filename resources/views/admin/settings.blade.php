@extends('layouts.admin.app')

@section('title', 'Application Settings')

@section('content')
<main class="flex bg-background-light dark:bg-background-dark">
<div class="mx-auto flex w-full max-w-4xl flex-col items-center">
    <!-- Success Message -->
    @if (session('success'))
        <div class="mt-4 w-full rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 p-4">
            <div class="flex items-center gap-3">
                <span class="text-green-600 dark:text-green-400 material-symbols-outlined">check_circle</span>
                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mt-4 w-full rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4">
            <div class="flex flex-col gap-2">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center gap-3">
                        <span class="text-red-600 dark:text-red-400 material-symbols-outlined">error</span>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ $error }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-8 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
        <!-- Header -->
        <div class="flex p-6 @container">
            <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-col justify-center">
                    <p class="text-[#111418] dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em]">Application Settings</p>
                    <p class="text-[#617589] dark:text-gray-400 text-base font-normal leading-normal">Configure your application settings</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="px-6 pb-6">
            @csrf
            @method('PUT')

            <!-- Application Name -->
            <div class="border-t border-gray-200 dark:border-gray-700 py-5">
                <label for="app_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Application Name
                </label>
                <input 
                    type="text" 
                    id="app_name" 
                    name="app_name" 
                    value="{{ old('app_name', $appName) }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="Enter application name"
                    required
                >
                @error('app_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">The name displayed throughout the application</p>
            </div>

            <!-- Support Email -->
            <div class="border-t border-gray-200 dark:border-gray-700 py-5">
                <label for="support_email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Support Email
                </label>
                <input 
                    type="email" 
                    id="support_email" 
                    name="support_email" 
                    value="{{ old('support_email', $supportEmail) }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="Enter support email"
                    required
                >
                @error('support_email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Email address used for support inquiries</p>
            </div>

            <!-- Application Logo -->
            <div class="border-t border-gray-200 dark:border-gray-700 py-5">
                <label for="app_logo" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Application Logo
                </label>
                
                <!-- Current Logo Preview -->
                @if ($appLogo)
                    <div class="mb-4 flex items-center gap-4">
                        <div class="h-20 w-20 rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <img src="{{ $appLogo }}" alt="Current Logo" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Current Logo</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Max size: 5MB</p>
                        </div>
                    </div>
                @endif

                <!-- File Input -->
                <div class="relative">
                    <input 
                        type="file" 
                        id="app_logo" 
                        name="app_logo"
                        accept="image/jpeg,image/jpg,image/png,image/gif"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent cursor-pointer"
                    >
                </div>
                @error('app_logo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Supported formats: JPEG, PNG, JPG, GIF</p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-start gap-4 border-t border-gray-200 dark:border-gray-700 pt-5">
                <button 
                    type="submit"
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90"
                >
                    <span class="truncate">Save Changes</span>
                </button>
                <a 
                    href="{{ route('admin.dashboard') }}"
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-300 dark:hover:bg-gray-600"
                >
                    <span class="truncate">Cancel</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Settings Info Card -->
    <div class="mt-8 w-full rounded-xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-6">
        <div class="flex gap-3">
            <span class="text-blue-600 dark:text-blue-400 material-symbols-outlined flex-shrink-0 mt-1">info</span>
            <div>
                <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Settings Information</h3>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                    <li>• Application settings are stored in the database and will be used throughout the system</li>
                    <li>• Logo changes will be reflected on the login page and dashboard</li>
                    <li>• Email changes will be used for all system notifications and support communications</li>
                    <li>• All changes are saved immediately and take effect globally</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</main>
@endsection
