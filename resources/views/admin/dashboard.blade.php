@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<main class="flex-1 p-8 overflow-y-auto">
<!-- PageHeading -->
<div class="flex flex-wrap justify-between gap-3 items-center">
<p class="text-gray-900 dark:text-gray-50 text-4xl font-black leading-tight tracking-[-0.033em]">{{ $currentMonthYear }}</p>
</div>
<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-700">
<p class="text-gray-700 dark:text-gray-300 text-base font-medium leading-normal">Total Users</p>
<p class="text-gray-900 dark:text-gray-50 tracking-light text-4xl font-bold leading-tight">{{ number_format($totalUsers) }}</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-700">
<p class="text-gray-700 dark:text-gray-300 text-base font-medium leading-normal">Active Exams</p>
<p class="text-gray-900 dark:text-gray-50 tracking-light text-4xl font-bold leading-tight">{{ number_format($activeExams) }}</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-700">
<p class="text-gray-700 dark:text-gray-300 text-base font-medium leading-normal">Total Questions</p>
<p class="text-gray-900 dark:text-gray-50 tracking-light text-4xl font-bold leading-tight">{{ number_format($totalQuestions) }}</p>
</div>
</div>

<!-- Monthly Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
<div class="flex flex-col gap-2 rounded-xl p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
<p class="text-blue-700 dark:text-blue-300 text-base font-medium leading-normal">{{ $currentMonthYear }} - New Users</p>
<p class="text-blue-900 dark:text-blue-50 tracking-light text-4xl font-bold leading-tight">{{ number_format($thisMonthUsers) }}</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
<p class="text-green-700 dark:text-green-300 text-base font-medium leading-normal">{{ $currentMonthYear }} - Completed Exams</p>
<p class="text-green-900 dark:text-green-50 tracking-light text-4xl font-bold leading-tight">{{ number_format($thisMonthCompletedExams) }}</p>
</div>
<div class="flex flex-col gap-2 rounded-xl p-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
<p class="text-yellow-700 dark:text-yellow-300 text-base font-medium leading-normal">{{ $currentMonthYear }} - On-going Exams</p>
<p class="text-yellow-900 dark:text-yellow-50 tracking-light text-4xl font-bold leading-tight">{{ number_format($thisMonthOngoingExams) }}</p>
</div>
</div>
<!-- User Lists Container -->
<div class="flex flex-col gap-8 mt-8">
<!-- On-going Exams Card -->
<div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-700 rounded-xl">
<!-- SectionHeader & SearchBar -->
<div class="flex flex-wrap justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 gap-4">
<div>
<h2 class="text-gray-900 dark:text-gray-50 text-[22px] font-bold leading-tight tracking-[-0.015em]">On-going Exams</h2>
<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Filtered for {{ $currentMonthYear }}</p>
</div>
<div class="w-full sm:w-72">
<label class="flex flex-col h-11 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full">
<div class="text-gray-500 dark:text-gray-400 flex bg-gray-100 dark:bg-gray-800 items-center justify-center pl-3.5 rounded-l-lg">
<span class="material-symbols-outlined text-xl">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-gray-50 focus:outline-0 focus:ring-2 focus:ring-primary focus:ring-inset border-none bg-gray-100 dark:bg-gray-800 h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 px-4 rounded-l-none pl-2 text-sm font-normal leading-normal" placeholder="Search on-going exams..."/>
</div>
</label>
</div>
</div>
<!-- Table -->
<div class="overflow-x-auto">
<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
<tr>
<th class="px-6 py-3" scope="col">User</th>
<th class="px-6 py-3" scope="col">Assigned Exam</th>
<th class="px-6 py-3" scope="col">Status</th>
</tr>
</thead>
<tbody>
@forelse($ongoingExams as $exam)
<tr class="bg-white dark:bg-background-dark border-b dark:border-gray-700">
<th class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap" scope="row">
<div class="flex items-center gap-3">
<div>
<div>{{ $exam->user->name }}</div>
<div class="text-xs text-gray-500 dark:text-gray-400">{{ $exam->user->email }}</div>
</div>
</div>
</th>
<td class="px-6 py-4">{{ $exam->exam->title }}</td>
<td class="px-6 py-4"><span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">On-going</span></td>
</tr>
@empty
<tr class="bg-white dark:bg-background-dark">
<th colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No on-going exams</th>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
<!-- Completed Exams Card -->
<div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-700 rounded-xl">
<div class="flex flex-wrap justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 gap-4">
<div>
<h2 class="text-gray-900 dark:text-gray-50 text-[22px] font-bold leading-tight tracking-[-0.015em]">Completed Exams</h2>
<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Filtered for {{ $currentMonthYear }}</p>
</div>
<div class="w-full sm:w-72">
<label class="flex flex-col h-11 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full">
<div class="text-gray-500 dark:text-gray-400 flex bg-gray-100 dark:bg-gray-800 items-center justify-center pl-3.5 rounded-l-lg">
<span class="material-symbols-outlined text-xl">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-gray-50 focus:outline-0 focus:ring-2 focus:ring-primary focus:ring-inset border-none bg-gray-100 dark:bg-gray-800 h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 px-4 rounded-l-none pl-2 text-sm font-normal leading-normal" placeholder="Search completed exams..."/>
</div>
</label>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
<tr>
<th class="px-6 py-3" scope="col">User</th>
<th class="px-6 py-3" scope="col">Assigned Exam</th>
<th class="px-6 py-3" scope="col">Completed Date</th>
<th class="px-6 py-3" scope="col">Status</th>
</tr>
</thead>
<tbody>
@forelse($completedExams as $exam)
<tr class="bg-white dark:bg-background-dark border-b dark:border-gray-700">
<th class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap" scope="row">
<div class="flex items-center gap-3">
<div>
<div>{{ $exam->user->name }}</div>
<div class="text-xs text-gray-500 dark:text-gray-400">{{ $exam->user->email }}</div>
</div>
</div>
</th>
<td class="px-6 py-4">{{ $exam->exam->title }}</td>
<td class="px-6 py-4">{{ $exam->finished_at?->format('Y-m-d') ?? 'N/A' }}</td>
<td class="px-6 py-4">
    @if($exam->data_status == 'passed')
        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Passed</span></td>
    @else
        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dasrk:bg-red-900 dark:text-red-300">Failed</span>
    @endif
</tr>
@empty
<tr class="bg-white dark:bg-background-dark">
<th colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No completed exams</th>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
<!-- Not Yet Started Card -->
<div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-gray-700 rounded-xl">
<div class="flex flex-wrap justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 gap-4">
<div>
<h2 class="text-gray-900 dark:text-gray-50 text-[22px] font-bold leading-tight tracking-[-0.015em]">Not Yet Started</h2>
<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Filtered for {{ $currentMonthYear }}</p>
</div>
<div class="w-full sm:w-72">
<label class="flex flex-col h-11 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full">
<div class="text-gray-500 dark:text-gray-400 flex bg-gray-100 dark:bg-gray-800 items-center justify-center pl-3.5 rounded-l-lg">
<span class="material-symbols-outlined text-xl">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-gray-50 focus:outline-0 focus:ring-2 focus:ring-primary focus:ring-inset border-none bg-gray-100 dark:bg-gray-800 h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 px-4 rounded-l-none pl-2 text-sm font-normal leading-normal" placeholder="Search unstarted exams..."/>
</div>
</label>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
<tr>
<th class="px-6 py-3" scope="col">User</th>
<th class="px-6 py-3" scope="col">Assigned Exam</th>
<th class="px-6 py-3" scope="col">Assigned Date</th>
<th class="px-6 py-3" scope="col">Status</th>
</tr>
</thead>
<tbody>
@forelse($notStartedExams as $exam)
<tr class="bg-white dark:bg-background-dark border-b dark:border-gray-700">
<th class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap" scope="row">
<div class="flex items-center gap-3">
<div>
<div>{{ $exam->user->name }}</div>
<div class="text-xs text-gray-500 dark:text-gray-400">{{ $exam->user->email }}</div>
</div>
</div>
</th>
<td class="px-6 py-4">{{ $exam->exam->title }}</td>
<td class="px-6 py-4">{{ $exam->created_at->format('Y-m-d') }}</td>
<td class="px-6 py-4"><span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Not Started</span></td>
</tr>
@empty
<tr class="bg-white dark:bg-background-dark">
<th colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No pending exams</th>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
</div>
</main>
@endsection
