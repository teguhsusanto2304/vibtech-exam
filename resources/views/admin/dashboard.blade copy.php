<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Vibtech Genesis - Analytics Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#005A9C",
                        "background-light": "#F8F9FA",
                        "background-dark": "#101922",
                        "success": "#28A745",
                        "danger": "#DC3545",
                        "text-light-primary": "#333333",
                        "text-light-secondary": "#6c757d",
                        "text-dark-primary": "#F8F9FA",
                        "text-dark-secondary": "#9fa6ad",
                        "border-light": "#dee2e6",
                        "border-dark": "#343a40",
                        "card-light": "#ffffff",
                        "card-dark": "#1a242f",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light-primary dark:text-text-dark-primary">
<div class="relative flex h-auto min-h-screen w-full">
<aside class="sticky top-0 h-screen w-64 flex-shrink-0 flex-col border-r border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark hidden lg:flex">
<div class="flex items-center gap-4 h-[65px] border-b border-solid border-border-light dark:border-border-dark px-6">
<div class="text-primary size-6">
<svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
</svg>
</div>
<h2 class="text-lg font-bold leading-tight tracking-[-0.015em] text-text-light-primary dark:text-text-dark-primary">Vibtech Genesis</h2>
</div>
<nav class="flex-1 p-4">
<ul class="flex flex-col gap-2">
<li>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.users') }}">
<span class="material-symbols-outlined text-xl">group</span>
<span class="text-sm font-semibold">Users List</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined text-xl">settings</span>
<span class="text-sm font-semibold">Exam Settings</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.question-banks') }}">
<span class="material-symbols-outlined text-xl">quiz</span>
<span class="text-sm font-semibold">Questions Bank</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 bg-primary/10 text-primary dark:text-primary transition-colors" href="#">
<span class="material-symbols-outlined text-xl">monitoring</span>
<span class="text-sm font-semibold">Reporting Dashboard</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined text-xl">history</span>
<span class="text-sm font-semibold">Audit Log</span>
</a>
</li>
</ul>
</nav>
</aside>
<div class="flex flex-1 flex-col">
<header class="sticky top-0 z-10 flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark px-6 sm:px-10 py-3">
<div class="flex items-center gap-4 text-text-light-primary dark:text-text-dark-primary lg:hidden">
<div class="text-primary size-6">
<svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
</svg>
</div>
<h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Vibtech Genesis</h2>
</div>
<div class="flex flex-1 justify-end gap-4 sm:gap-6">
<button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 w-10 bg-transparent text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 transition-colors duration-200">
<span class="material-symbols-outlined">notifications</span>
</button>
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="User profile picture with a gradient background" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA95o6t7LldDZNvXunmUglS9ma8zNEjec1lCkn5v-kIwCCn0s2DUpwRhATjdUGTuXlW2GT659niGV45b0vtuSMItto_iGtpR-tzw5BQxztQH4Wb3RNbIEw_xt7rghstmHN6daE8R7mBpd3mpiD9amPCcCjJxY0XeyzPPl8aJC0dfi0gvzORaUBfuFTj2Xl1QS849quLDivifpH8SslTi1mPki3hyBVHN_wSZDljLCnrZaON3pNQTywRsaNmwegMipeuel2a_FlCrs6E");'></div>
</div>
</header>
<main class="flex h-full grow flex-col">
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
<div class="flex flex-col gap-8">
<div class="flex flex-wrap items-center justify-between gap-4">
<h1 class="text-text-light-primary dark:text-text-dark-primary text-3xl sm:text-4xl font-black leading-tight tracking-[-0.033em]">Analytics Dashboard</h1>
<div class="flex flex-wrap items-center gap-4">
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-card-light dark:bg-card-dark text-text-light-secondary dark:text-text-dark-secondary text-sm font-bold leading-normal tracking-[0.015em] border border-border-light dark:border-border-dark hover:border-primary transition-colors duration-200">
<span class="material-symbols-outlined text-base">calendar_today</span>
<span class="truncate">Last 30 Days</span>
<span class="material-symbols-outlined text-base">expand_more</span>
</button>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity duration-200">
<span class="material-symbols-outlined text-base">download</span>
<span class="truncate">Export Results</span>
</button>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<div class="flex flex-col gap-2 rounded-xl p-6 border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark">
<p class="text-text-light-secondary dark:text-text-dark-secondary text-base font-medium leading-normal">Active Users</p>
<p class="text-text-light-primary dark:text-text-dark-primary tracking-tight text-3xl font-bold leading-tight">1,204</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark">
<p class="text-text-light-secondary dark:text-text-dark-secondary text-base font-medium leading-normal">Deactivated Users</p>
<p class="text-text-light-primary dark:text-text-dark-primary tracking-tight text-3xl font-bold leading-tight">89</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark">
<p class="text-text-light-secondary dark:text-text-dark-secondary text-base font-medium leading-normal">Average Score</p>
<p class="text-text-light-primary dark:text-text-dark-primary tracking-tight text-3xl font-bold leading-tight">85%</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark">
<p class="text-text-light-secondary dark:text-text-dark-secondary text-base font-medium leading-normal">Average Duration</p>
<p class="text-text-light-primary dark:text-text-dark-primary tracking-tight text-3xl font-bold leading-tight">45m 30s</p>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
<div class="lg:col-span-3 flex flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-6 bg-card-light dark:bg-card-dark">
<div class="flex flex-col">
<p class="text-text-light-primary dark:text-text-dark-primary text-lg font-bold leading-normal">Attempt Distribution</p>
<p class="text-text-light-secondary dark:text-text-dark-secondary text-sm">Total attempts in the last 30 days</p>
</div>
<div class="grid min-h-[280px] grid-flow-col gap-6 items-end justify-items-center pt-4 px-3">
<div class="flex flex-col items-center gap-2 w-full h-full justify-end group">
<div class="relative w-full h-full flex items-end">
<div class="w-full bg-primary/20 rounded-t-lg group-hover:bg-primary/40 transition-all duration-300" style="height: 90%;"></div>
<div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded-md py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">2,850</div>
</div>
<p class="text-text-light-secondary dark:text-text-dark-secondary text-[13px] font-bold leading-normal tracking-[0.015em]">1st Attempt</p>
</div>
<div class="flex flex-col items-center gap-2 w-full h-full justify-end group">
<div class="relative w-full h-full flex items-end">
<div class="w-full bg-primary/20 rounded-t-lg group-hover:bg-primary/40 transition-all duration-300" style="height: 70%;"></div>
<div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded-md py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">1,720</div>
</div>
<p class="text-text-light-secondary dark:text-text-dark-secondary text-[13px] font-bold leading-normal tracking-[0.015em]">2nd Attempt</p>
</div>
<div class="flex flex-col items-center gap-2 w-full h-full justify-end group">
<div class="relative w-full h-full flex items-end">
<div class="w-full bg-primary/20 rounded-t-lg group-hover:bg-primary/40 transition-all duration-300" style="height: 40%;"></div>
<div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded-md py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">660</div>
</div>
<p class="text-text-light-secondary dark:text-text-dark-secondary text-[13px] font-bold leading-normal tracking-[0.015em]">3rd+ Attempt</p>
</div>
</div>
</div>
<div class="lg:col-span-2 flex flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-6 bg-card-light dark:bg-card-dark">
<div class="flex flex-col">
<p class="text-text-light-primary dark:text-text-dark-primary text-lg font-bold leading-normal">Overall Pass Rate</p>
<div class="flex gap-1 items-center">
<p class="text-text-light-secondary dark:text-text-dark-secondary text-sm">Last 30 Days</p>
<p class="text-success text-sm font-medium leading-normal flex items-center gap-0.5"><span class="material-symbols-outlined text-sm">arrow_upward</span>+1.8%</p>
</div>
</div>
<div class="flex-1 flex items-center justify-center py-4">
<div class="relative size-48">
<svg class="size-full" viewBox="0 0 36 36">
<path class="stroke-current text-danger" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4"></path>
<path class="stroke-current text-success" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-dasharray="78, 100" stroke-dashoffset="0" stroke-width="4"></path>
</svg>
<div class="absolute inset-0 flex flex-col items-center justify-center">
<span class="text-text-light-primary dark:text-text-dark-primary text-4xl font-bold">78%</span>
<span class="text-text-light-secondary dark:text-text-dark-secondary text-sm">Pass</span>
</div>
</div>
</div>
<div class="flex justify-center gap-6">
<div class="flex items-center gap-2">
<div class="size-3 rounded-full bg-success"></div>
<p class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-medium">Pass (4,080)</p>
</div>
<div class="flex items-center gap-2">
<div class="size-3 rounded-full bg-danger"></div>
<p class="text-text-light-secondary dark:text-text-dark-secondary text-sm font-medium">Fail (1,150)</p>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
</div>

</body></html>