<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Vibtech Genesis')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#005A9C",
                        "background-light": "#F5F7FA",
                        "background-dark": "#101922",
                    },
                    fontFamily: { "display": ["Inter", "sans-serif"] },
                },
            },
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
<div class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden" style='font-family: Inter, "Noto Sans", sans-serif;'>
<div class="layout-container flex h-full grow flex-col">
<div class="px-4 flex flex-1 justify-center py-5">
<div class="layout-content-container flex flex-col w-full max-w-md">
<div class="flex flex-col items-center mb-8">
<img class="h-12 w-auto mb-4" data-alt="Vibtech Genesis Logo" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCziq1bTMarfttnlutU9XXECXYIcm-eA4VthAvzXlEwJTXyPMyOvam9Q1_ycZF4Q1o9Z8IUM6Kh9ChJrgZQgGvZxfLJsYdgW5xiA9CFCLL7VNt6sflvg6KlzkA2n06ZTthfdtA1O30TYxP0t8ufTfxjP1Pig0tigvKt_LZ5_GV3VVGzh8RNu7X2BJKoHADG_MrDCVKp5-P2LB8tIJtRUJk9RJ6YGRec4lwyPiSYC9_Jn9B8ZYaxitmIHLkuKHPy8_jmsZhpstqYRhVR"/>
</div>
<div class="flex flex-col gap-3 p-4 text-center">
<p class="text-text-light dark:text-text-dark text-4xl font-black leading-tight tracking-[-0.033em]">Vibtech Genesis Examination Portal</p>
<p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-normal leading-normal">Welcome back. Please log in to continue.</p>
</div>
    @if ($errors->any())
    <x-alert-message type="error">
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-8 mt-6">
<!-- START: Added Form Tag and CSRF -->
<form method="POST" action="{{ route('login.submit') }}">
    @csrf

    <div class="flex max-w-[480px] flex-wrap items-end gap-4 py-3 w-full">
        <label class="flex flex-col min-w-40 flex-1">
        <p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-medium leading-normal pb-2">Email Address</p>
        <!-- Added name="email" attribute -->
        <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-0 border border-[#dbe0e6] dark:border-gray-600 bg-white dark:bg-gray-700 focus:border-primary h-14 placeholder:text-text-secondary-light dark:placeholder:text-gray-400 p-[15px] text-base font-normal leading-normal" 
               placeholder="Enter your email" 
               name="email" 
               type="email" 
               required 
               autofocus 
               value=""/>
        </label>
    </div>
    <div class="flex max-w-[480px] flex-wrap items-end gap-4 py-3 w-full">
        <label class="flex flex-col min-w-40 flex-1">
        <p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-medium leading-normal pb-2">Password</p>
        <div class="flex w-full flex-1 items-stretch rounded-lg">
            <!-- Added name="password" attribute -->
            <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-l-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-0 border border-[#dbe0e6] dark:border-gray-600 bg-white dark:bg-gray-700 focus:border-primary h-14 placeholder:text-text-secondary-light dark:placeholder:text-gray-400 p-[15px] border-r-0 pr-2 text-base font-normal leading-normal" 
                   placeholder="Enter your password" 
                   type="password" 
                   name="password"
                   required
                   value=""/>
            <div class="text-text-secondary-light dark:text-text-secondary-dark flex border border-[#dbe0e6] dark:border-gray-600 bg-white dark:bg-gray-700 items-center justify-center px-3 rounded-r-lg border-l-0 cursor-pointer">
            <span class="material-symbols-outlined">visibility</span>
            </div>
        </div>
        </label>
    </div>
    <div class="flex px-4 py-3 justify-center mt-4">
    <button class="bg-primary flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 flex-1 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-opacity-90 transition-opacity" type="submit">
    <span class="truncate">Login</span>
    </button>
    </div>
    <div class="mt-4">
    
    </div>
</form>
<!-- END: Added Form Tag -->
</div>
<div class="mt-8 text-center text-text-secondary-light dark:text-text-secondary-dark text-sm">
<a class="hover:underline" href="#">Support</a>
<span class="mx-2">·</span>
<a class="hover:underline" href="#">Privacy Policy</a>
</div>
</div>
</div>
</div>
</div>
</body></html>
