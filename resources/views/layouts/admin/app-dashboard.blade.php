<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Vibtech Genesis')</title>

    {{-- Tailwind + Fonts --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#005A9C",
                        "background-light": "#F8F9FA",
                        "background-dark": "#101922",
                        success: "#28A745",
                        danger: "#DC3545",
                        "text-light-primary": "#333333",
                        "text-light-secondary": "#6c757d",
                        "text-dark-primary": "#F8F9FA",
                        "text-dark-secondary": "#9fa6ad",
                        "border-light": "#dee2e6",
                        "border-dark": "#343a40",
                        "card-light": "#ffffff",
                        "card-dark": "#1a242f",
                    },
                    fontFamily: { display: ["Inter", "sans-serif"] },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-text-light-primary dark:text-text-dark-primary">

<div class="relative flex min-h-screen w-full">

    {{-- Sidebar --}}
    @include('layouts.admin.sidebar')

    <div class="flex flex-1 flex-col overflow-y-auto">

        {{-- Header --}}
        @include('layouts.admin.header')

        {{-- Main Content --}}
        <main class="flex h-full grow flex-col">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        @include('layouts.admin.footer')

    </div>
</div>

@stack('scripts')

</body>
</html>
