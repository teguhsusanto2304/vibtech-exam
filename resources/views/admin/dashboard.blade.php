@extends('layouts.admin.app-dashboard')

@section('title', 'Analytics Dashboard')

@section('content')
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
@endsection
