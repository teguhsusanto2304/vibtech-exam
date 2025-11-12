@extends('layouts.admin.app')

@section('title', 'User Management')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.1/dist/cdn.min.js">
</script>
@if (session('success'))
<x-alert-message type="success">
    <ul class="list-disc list-inside space-y-1 text-sm">
        <li>
            {{ session('success') }}
        </li>
    </ul>
</x-alert-message>
@endif
<main class="layout-container flex h-full grow flex-col">
    <div class="px-4 sm:px-6 lg:px-8 xl:px-10 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col w-full max-w-6xl flex-1 gap-6">
            <!-- Breadcrumbs & Page Heading -->
                <div class="flex flex-col gap-4">
                    <!-- Breadcrumbs Component -->
                        <div class="flex flex-wrap gap-2">
                        </div>
                        <!-- PageHeading Component -->
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <h1 class="text-[#343A40] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em] min-w-72">
                                    {{ $user->name }}
                                </h1>
                                <a href="{{ route('admin.users') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-200 dark:bg-gray-700 text-[#343A40] dark:text-gray-200 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <span class="truncate">
                                        Back to All Users
                                    </span>
                                </a>
                            </div>
                        </div>
                        <x-user-header :user="$user" />
                        <!-- Examination History Section -->
                            @if($user->role=='user')
                            <div class="flex flex-col gap-4">
                                <!-- SectionHeader Component -->
                                    <div class="flex flex-wrap items-center justify-between gap-4">
                                        <h2 class="text-[#343A40] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">
                                            Examination Attempt
                                        </h2>
                                        <a href="{{ route('admin.users.assign-exam',['id'=>
                                            $user->id]) }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary dark:bg-primary-700 text-white dark:text-blue-200 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-blue-700 dark:hover:bg-primary-600">
                                            <x-heroicon-o-folder-plus class="w-5 h-5 bold mr-1" />
                                            <span class="truncate">
                                                Add Exam
                                            </span>
                                        </a>
                                    </div>
                                    <!-- Data Table (Attempt History) -->
                                        <div class="overflow-x-auto bg-white dark:bg-[#182431] rounded-xl border border-[#E0E0E0] dark:border-gray-700">
                                            <table class="min-w-full text-sm text-left">
                                                <thead class="border-b border-[#E0E0E0] dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 uppercase">
                                                    <tr>
                                                        <th class="px-6 py-4 font-medium">
                                                        </th>
                                                        <th class="px-6 py-4 font-medium">
                                                            Exam Name
                                                        </th>
                                                        <th class="px-6 py-4 font-medium">
                                                            Status
                                                        </th>
                                                        <th class="px-6 py-4 font-medium">
                                                            Attempts Used
                                                        </th>
                                                        <th class="px-6 py-4 font-medium">
                                                            Last Score
                                                        </th>
                                                        <th class="px-6 py-4 font-medium">
                                                            Duration
                                                        </th>
                                                        <th class="px-6 py-4 font-medium">
                                                            Completion Date
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $index=0; @endphp
            @forelse($userExams as $attempt)
            @php $index++; @endphp
                                                    <tr class="border-b border-[#E0E0E0] dark:border-gray-700">
                                                        <td class="px-6 py-4">
                                                            <button 
                            class="text-gray-500 dark:text-gray-400" 
                            onclick="toggleAccordion({{ $index }})" 
                            data-id="{{ $index }}"
                        >
                                                                <span class="material-symbols-outlined text-xl">
                                                                    expand_more
                                                                </span>
                                                            </button>
                                                        </td>
                                                        <td class="px-6 py-4 font-medium text-[#343A40] dark:text-white">
                                                            {{ $attempt->exam->title }}
                                                            <p>
                                                                @if($attempt->end_date)
                                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                                                    {{ $attempt->active_date->format('d-M-Y H:i') }}
                                                                </span>
                                                                -
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                                                    {{ $attempt->end_date->format('d-M-Y H:i') }}
                                                                </span>
                                                                @else
                                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                                                    —
                                                                </span>
                                                                @endif
                                                            </p>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            @php
                            $statusColors = [
                                'cancel' => 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-300',
                                'pending' => 'bg-orange-100 text-orange-700 dark:bg-orange-800 dark:text-orange-300',
                                'started' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                            ];
                        @endphp
                        @if($attempt->data_status=='pending')
                                                            <select 
                                class="text-xs px-1.5 py-0.5 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-400"
                                name="data_status"
                            >
                                                                @foreach(\App\Models\UserExam::STATUSES as $key => $label)
                                                                <option value="{{ $key }}" {{ $attempt->
                                                                    data_status === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                            @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$attempt->
                                                                data_status] ?? 'bg-gray-200 text-gray-800' }}">
                            {{ ucfirst($attempt->data_status) }}
                                                            </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 font-medium text-green-600 dark:text-green-400">
                                                            {{ $attempt->attempts_used }}
                                                        </td>
                                                        <td class="px-6 py-4 font-medium text-green-600 dark:text-green-400">
                                                            {{ $attempt->data_score }}%
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            {{ optional($attempt->finished_at)->format('d M Y') ?? '—' }}
                                                        </td>
                                                    </tr>
                                                    {{-- Expanded Section: Itemized Responses --}}
                                                    <tr>
                                                        <td colspan="6" class="p-0">
                                                            <div 
            id="content-{{ $index }}" 
            class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out"
        >
                                                                <div class="bg-background-light dark:bg-background-dark p-4">
                                                                    <h3 class="text-base font-bold mb-3 px-2 text-[#343A40] dark:text-white">
                                                                        Itemized Response History
                                                                    </h3>
                                                                    <div class="overflow-x-auto">
                                                                        <table class="min-w-full text-sm text-left">
                                                                            <thead class="text-xs text-gray-500 dark:text-gray-400">
                                                                                <tr>
                                                                                    <th class="px-2 py-2 font-medium">
                                                                                        Question #
                                                                                    </th>
                                                                                    <th class="px-2 py-2 font-medium">
                                                                                        User's Answer
                                                                                    </th>
                                                                                    <th class="px-2 py-2 font-medium">
                                                                                        Correct Answer
                                                                                    </th>
                                                                                    <th class="px-2 py-2 font-medium text-right">
                                                                                        Result
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($attempt->exam->masterExamQuestions as $index => $masterQuestion)
                                @php
                                    $question = $masterQuestion->question ?? null;
                                    // Find the user's answer (if any) for this question
                                    $userAnswer = $attempt->answers
                                        ->firstWhere('examQuestion.question_id', $question->id ?? null);
                                @endphp
                                                                                <tr class="border-b border-[#E0E0E0] dark:border-gray-700">
                                                                                    <td class="px-2 py-3 font-medium">
                                                                                        {{ $index + 1 }}
                                                                                    </td>
                                                                                    <td class="px-2 py-3 
                                        {{ optional($userAnswer)->
                                                                                        is_correct ? 'text-green-600' : 
                                        ($userAnswer ? 'text-red-600 dark:text-red-400' : 'text-gray-400') }}">
                                        @if($userAnswer)
                                            Option {{ $userAnswer->user_option }}
                                        @else
                                                                                        <em>
                                                                                            No answer
                                                                                        </em>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="px-2 py-3">
                                                                                        Option {{ $question->correct_option ?? '—' }}
                                                                                    </td>
                                                                                    <td class="px-2 py-3 text-right">
                                                                                        @if($userAnswer)
                                            @if($userAnswer->is_correct)
                                                                                        <span class="material-symbols-outlined text-green-500">
                                                                                            check_circle
                                                                                        </span>
                                                                                        @else
                                                                                        <span class="material-symbols-outlined text-red-500">
                                                                                            cancel
                                                                                        </span>
                                                                                        @endif
                                        @else
                                                                                        <span class="material-symbols-outlined text-gray-400">
                                                                                            help
                                                                                        </span>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                            No exam attempts found.
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <script>
                                function toggleAccordion(id) {
        const contentRow = document.getElementById(`content-${id}`);
        const icon = document.querySelector(`button[data-id="${id}"] .material-symbols-outlined`);

        // Toggle open/close state
        const isOpen = contentRow.classList.contains('open');

        // Close all accordion rows first (optional if you want single open behavior)
        document.querySelectorAll('[id^="content-"]').forEach(row => {
            row.style.maxHeight = '0';
            row.classList.remove('open');
        });
        document.querySelectorAll('button[data-id] .material-symbols-outlined').forEach(iconEl => {
            iconEl.textContent = 'expand_more';
        });

        // Open the clicked one if it was not open
        if (!isOpen) {
            contentRow.style.maxHeight = contentRow.scrollHeight + 'px';
            contentRow.classList.add('open');
            icon.textContent = 'expand_less';
        }
    }
                            </script>
                        </main>
                        @endsection