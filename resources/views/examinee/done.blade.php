<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Vibtech Genesis - Exam Results</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark">
<div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<div class="flex flex-1 justify-center py-5">
<div class="layout-content-container flex flex-col max-w-[960px] flex-1">
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f0f2f4] dark:border-b-background-dark/20 px-10 py-3">
<div class="flex items-center gap-4 text-[#111418] dark:text-white">
<div class="size-6 text-primary">
<svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
</svg>
</div>
<h2 class="text-[#111418] dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Vibtech Genesis Examination Portal</h2>
</div>
<div class="flex flex-1 justify-end gap-8">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="User profile picture" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB-oMJnX9jcQR2BLSWFFe6IX5mH6hGbe0ZPxxzE3FHaGs7WI6Dkq6hYr8IlkmtTAZ6TYZCFeFO0ejp7FdAqhSq5y5OCoCtGiL0Y3ag3pomMouRKxu6AxFDnpErUe5kMbTFUA9cIen5XRB9NXxZ9YxWouSc_tfTW_rLVyLrFEYmp_sgewyWZIipeatfyhsiv5JXk9DaTbkMI-2XKt3Dz18cLn-p6Az_1TCatQt0kv6In7ngNFrR6yjHHDU4xiEl84huaQppwOpuJU5rm");'></div>
</div>
</header>
<main class="p-4 sm:p-10 flex-1 flex flex-col items-center">
<div class="w-full max-w-2xl bg-white dark:bg-background-dark/50 rounded-xl shadow-lg p-8 space-y-8">
<div class="flex flex-col items-center gap-4">
<div class="flex h-24 w-24 items-center justify-center rounded-full bg-green-100 dark:bg-green-500/20">
<span class="material-symbols-outlined text-5xl text-green-600 dark:text-green-400">check</span>
</div>
<div class="text-center">
<p class="text-[#111418] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Exam Results</p>
<p class="text-green-600 dark:text-green-400 text-lg font-medium leading-normal mt-2">Congratulations, you have passed!</p>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
<div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-[#dbe0e6] dark:border-white/10 bg-background-light dark:bg-background-dark">
<p class="text-[#617589] dark:text-gray-400 text-base font-medium leading-normal">Total Questions</p>
<p class="text-[#111418] dark:text-white tracking-light text-2xl font-bold leading-tight">50</p>
</div>
<div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-[#dbe0e6] dark:border-white/10 bg-background-light dark:bg-background-dark">
<p class="text-[#617589] dark:text-gray-400 text-base font-medium leading-normal">Correct Answers</p>
<p class="text-[#111418] dark:text-white tracking-light text-2xl font-bold leading-tight">42</p>
</div>
<div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-[#dbe0e6] dark:border-white/10 bg-background-light dark:bg-background-dark">
<p class="text-[#617589] dark:text-gray-400 text-base font-medium leading-normal">Score</p>
<p class="text-[#111418] dark:text-white tracking-light text-2xl font-bold leading-tight">85%</p>
</div>
<div class="flex min-w-[158px] flex-1 flex-col col-span-1 sm:col-span-2 md:col-span-3 gap-2 rounded-lg p-6 border border-[#dbe0e6] dark:border-white/10 bg-background-light dark:bg-background-dark">
<p class="text-[#617589] dark:text-gray-400 text-base font-medium leading-normal">Attempts Used</p>
<p class="text-[#111418] dark:text-white tracking-light text-2xl font-bold leading-tight">1 of 3</p>
</div>
</div>
<p class="text-[#111418] dark:text-gray-300 text-base font-normal leading-normal pb-3 pt-1 px-4 text-center">
                                You have successfully completed your certification. Please log out to secure your session.
                            </p>
<div class="flex px-4 py-3 justify-center">
<button id="loggoutBtn" class="flex w-full sm:w-auto min-w-[120px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-8 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
<span class="truncate">Logout</span>
</button>
</div>
</div>
</main>
</div>
</div>
</div>
</div>
</body>
<form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
<script>
    const logoutBtn = document.getElementById('loggoutBtn');
    logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('logoutForm').submit();
    });
</script>
</html>