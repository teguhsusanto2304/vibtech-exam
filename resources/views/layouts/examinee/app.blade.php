<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Vibtech Genesis Examination Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#dbe0e6] dark:border-b-gray-700 px-4 sm:px-10 py-4 bg-white dark:bg-background-dark">
                <div class="flex items-center gap-4 text-gray-800 dark:text-white">
                    <div class="size-6 text-primary">
                        <!-- Logo SVG -->
                        <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_6_330)">
                                <path clip-rule="evenodd" d="M24 0.757355L47.2426 24L24 47.2426L0.757355 24L24 0.757355ZM21 35.7574V12.2426L9.24264 24L21 35.7574Z" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                            <defs>
                                <clippath id="clip0_6_330">
                                    <rect fill="white" height="48" width="48"></rect>
                                </clippath>
                            </defs>
                        </svg>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold tracking-tight text-gray-800">Vibtech Genesis Examination Portal</h2>
                </div>

                <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="#">
<a href="#" 
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
   class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-red-500">
    
    <span class="material-symbols-outlined text-xl">logout</span>
    <span class="text-sm font-semibold">Exit</span>
</a>

{{-- Hidden Form for secure POST request --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
</a>
            </header>

    <!-- Main content -->
        @yield('content')

    @yield('scripts')
</body>
</html>
