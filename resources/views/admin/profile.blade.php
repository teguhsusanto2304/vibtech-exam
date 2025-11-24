@extends('layouts.admin.app')



@section('title', $pageTitle)

@section('content')
<main class="flex bg-background-light dark:bg-background-dark">
<div class="mx-auto flex w-full max-w-4xl flex-col items-center">
<div class="mt-8 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
<div class="flex p-6 @container">
<div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center">
<div class="flex flex-col justify-center">
<p class="text-[#111418] dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em]">{{ auth()->user()->name }}</p>
<p class="text-[#617589] dark:text-gray-400 text-base font-normal leading-normal">{{ ucfirst(auth()->user()->role) }}</p>
</div>
</div>
</div>
<div class="px-6 pb-6 grid grid-cols-[30%_1fr] sm:grid-cols-[25%_1fr] gap-x-6">
<div class="col-span-2 grid grid-cols-subgrid border-t border-t-gray-200 dark:border-t-gray-700 py-5">
<p class="text-[#617589] dark:text-gray-400 text-sm font-medium leading-normal">Company</p>
<p class="text-[#111418] dark:text-white text-sm font-normal leading-normal">{{ auth()->user()->company }}</p>
</div>
<div class="col-span-2 grid grid-cols-subgrid border-t border-t-gray-200 dark:border-t-gray-700 py-5">
<p class="text-[#617589] dark:text-gray-400 text-sm font-medium leading-normal">Email</p>
<p class="text-[#111418] dark:text-white text-sm font-normal leading-normal">{{ auth()->user()->email }}</p>
</div>
<div class="col-span-2 grid grid-cols-subgrid border-t border-t-gray-200 dark:border-t-gray-700 py-5">
<p class="text-[#617589] dark:text-gray-400 text-sm font-medium leading-normal">Role</p>
<p class="text-[#111418] dark:text-white text-sm font-normal leading-normal">{{ ucfirst(auth()->user()->role) }}</p>
</div>
</div>
<div class="flex px-6 py-5 justify-start border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-b-xl">
<a href="{{ route('admin.change-password') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
<span class="truncate">Reset Password</span>
</a>
</div>
</div>
</div>
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 hidden">
<div class="w-full max-w-md rounded-xl bg-white dark:bg-gray-800 shadow-2xl">
<div class="flex flex-col items-center p-8 text-center">
<div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/50 text-primary">
<span class="material-symbols-outlined !text-4xl">mark_email_read</span>
</div>
<h3 class="text-xl font-bold text-[#111418] dark:text-white">Check Your Email</h3>
<p class="mt-2 text-base text-[#617589] dark:text-gray-400">A password reset link has been sent to your registered email address. Please follow the instructions to create a new password.</p>
<button class="mt-6 flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]">
<span class="truncate">OK</span>
</button>
</div>
</div>
</div>
</main>

@endsection
