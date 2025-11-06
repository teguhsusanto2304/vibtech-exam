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

<body class="bg-background-light dark:bg-background-dark font-display">
    <div class="relative flex min-h-screen w-full overflow-x-hidden">
        <!-- Sidebar -->
        @include('layouts.admin.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-y-auto">
            <!-- Header -->
            @include('layouts.admin.header')

            <!-- Page Body -->
            <main class="flex-1 flex flex-col p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.admin.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
