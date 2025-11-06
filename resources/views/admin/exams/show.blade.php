@extends('layouts.admin.app')

@section('title', 'Exam Questions (Read-Only)')

@section('content')

{{-- Display success/error alerts, kept for visual consistency if the page loads after an action, but no actions are possible here. --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show"
     class="p-4 mb-4 bg-green-100 border-l-4 border-green-500 rounded-md shadow-sm"
     role="alert">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-semibold text-green-800">
                {{ session('success') }}
            </p>
        </div>

        <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg p-1.5 hover:bg-green-200"
                @click="show = false">
            <span class="sr-only">Dismiss</span>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10L4.293 5.707a1 1 0 010-1.414z"
                      clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</div>
@endif
@if (session('error'))
<div x-data="{ show: true }" x-show="show"
     class="p-4 mb-4 bg-red-100 border-l-4 border-red-500 rounded-md shadow-sm"
     role="alert">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-semibold text-red-800">
                {{ session('error') }}
            </p>
        </div>

        <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg p-1.5 hover:bg-red-200"
                @click="show = false">
            <span class="sr-only">Dismiss</span>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10L4.293 5.707a1 1 0 010-1.414z"
                      clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</div>
@endif

    <div class="flex flex-wrap items-center justify-between py-5">
        <h1 class="text-[#343A40] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em] min-w-72">{{ $exam->title }} (Read-Only)</h1>

        {{-- READ-ONLY: Status and Back Button --}}
        <div class="flex items-center space-x-4">
            <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                Status: {!! $exam->data_status_badge !!}
            </div>

            <a href="{{ route('admin.exams') }}"
                class="flex items-center justify-center h-10 px-4
                        bg-gray-500 hover:bg-gray-600
                        dark:bg-gray-700 dark:hover:bg-gray-600
                        text-white text-sm font-bold
                        rounded-lg shadow-md"> <span class="truncate">Back to All Exams</span>
            </a>
        </div>
    </div>

    {{-- Exam Details Card (Remains Read-Only) --}}
    <div class="bg-white dark:bg-[#182431] p-6 rounded-xl border border-[#E0E0E0] dark:border-gray-700">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-x-4 gap-y-6">
            <div class="flex flex-col gap-1">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Title</p>
                <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $exam->title }}</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Questions</p>
                <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $exam->questions }}</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Durations</p>
                <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $exam->duration }} mins</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Created Date</p>
                <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $exam->created_at->format('d-m-Y') }}</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Pass Mark</p>
                <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $exam->pass_mark }} %</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Status</p>
                <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{!! $exam->data_status_badge !!}</p>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        {{-- READ-ONLY: Left Panel: Question Bank (No form, no actions) --}}
        <div class="flex flex-col bg-content-light dark:bg-content-dark rounded-xl border border-border-light dark:border-border-dark">
            <div class="p-4 border-b border-border-light dark:border-border-dark">
                <h2 class="text-text-light dark:text-text-dark text-lg font-bold">Question Bank</h2>
                <p class="text-subtle-light dark:text-subtle-dark text-sm">Review available questions and their assignment status.</p>
            </div>

            <!-- Question List -->
            <div class="p-2 space-y-2 overflow-y-auto" style="max-height: 500px;">
                @forelse ($questions as $question)
                    @php
                        $isChecked = $exam->examQuestions->contains($question->id);
                    @endphp

                    <div
                        class="flex items-center justify-between p-3 rounded-lg
                            {{ $isChecked ? 'bg-green-100 dark:bg-green-900/30' : 'hover:bg-background-light dark:hover:bg-background-dark' }}"
                    >
                        <div class="flex items-center gap-3">
                            {{-- Replaced checkbox with static indicator --}}
                            @if ($isChecked)
                                <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
                            @else
                                <span class="material-symbols-outlined text-gray-400 text-lg">radio_button_unchecked</span>
                            @endif
                            <p class="text-sm text-text-light dark:text-text-dark">{{ $question->question_stem }}</p>
                        </div>
                    </div>
                @empty
                    <p class="p-3 text-subtle-light dark:text-subtle-dark">No questions available in the bank.</p>
                @endforelse
            </div>

            <div class="p-4 border-t border-border-light dark:border-border-dark mt-auto text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">Assignment actions are disabled in read-only mode.</p>
            </div>
        </div>

        {{-- READ-ONLY: Right Panel: Assigned Questions (No Clear All, No Remove Single) --}}
        <div class="flex flex-col bg-content-light dark:bg-content-dark rounded-xl border border-border-light dark:border-border-dark">
            <div class="p-4 border-b border-border-light dark:border-border-dark flex justify-between items-center">
                <div>
                    <h2 class="text-text-light dark:text-text-dark text-lg font-bold">Questions in Exam</h2>
                    <p class="text-subtle-light dark:text-subtle-dark text-sm">
                        Currently {{ $exam->examQuestions->count() }} of {{ $exam->questions }} questions assigned.
                    </p>
                </div>
                {{-- Removed "Clear All" Button --}}
            </div>

            @if($exam->examQuestions->count() > 0)
                <!-- Assigned Question List -->
                <div class="p-2 space-y-2 overflow-y-auto" style="max-height: 500px;">
                    @foreach($exam->examQuestions as $index => $question)
                        <div class="flex items-center justify-between p-3 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark">
                            <div class="flex items-center gap-3">
                                {{-- Removed Drag Indicator --}}
                                <span class="font-semibold text-sm w-6 text-gray-500 dark:text-gray-400">{{ $index + 1 }}.</span>
                                <p class="text-sm text-text-light dark:text-text-dark">{{ $question->question_stem }}</p>
                            </div>
                            {{-- Removed REMOVE SINGLE QUESTION BUTTON --}}
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="p-4 flex-grow flex flex-col items-center justify-center text-center">
                    <span class="material-symbols-outlined text-6xl text-subtle-light/50 dark:text-subtle-dark/50">quiz</span>
                    <h3 class="font-bold mt-4">This exam has no questions yet</h3>
                    <p class="text-sm text-subtle-light dark:text-subtle-dark max-w-xs mt-1">
                        No questions have been assigned to this exam.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

{{-- Removed CUSTOM CONFIRMATION MODAL and JavaScript for read-only mode --}}
