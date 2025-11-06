@extends('layouts.admin.app')

@section('title', 'Question Details')

@section('content')
<div class="w-full mt-8 bg-white dark:bg-gray-900 p-6 rounded-xl shadow">

    <div class="space-y-4">
        <div>
            <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300">Question:</h2>
            <p class="text-gray-900 dark:text-gray-100">{{ $question->question_stem }}</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div>
                <p class="font-semibold text-gray-600 dark:text-gray-400">Topic:</p>
                <p>{{ $question->topic }}</p>
            </div>

            <div>
                <p class="font-semibold text-gray-600 dark:text-gray-400">Difficulty Level:</p>
                <p>{{ ucfirst($question->difficulty_level) }}</p>
            </div>
        </div>

        <div class="mt-4">
            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Options:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
                <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <p><strong>A:</strong> {{ $question->option_a }}</p>
                </div>
                <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <p><strong>B:</strong> {{ $question->option_b }}</p>
                </div>
                <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <p><strong>C:</strong> {{ $question->option_c }}</p>
                </div>
                <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <p><strong>D:</strong> {{ $question->option_d }}</p>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <p class="font-semibold text-green-600 dark:text-green-400">Correct Option:</p>
            <p class="text-lg font-bold">{{ strtoupper($question->correct_option) }}</p>
        </div>

        @if ($question->explanation)
        <div class="mt-4">
            <p class="font-semibold text-gray-700 dark:text-gray-300">Explanation:</p>
            <p class="text-gray-900 dark:text-gray-100">{{ $question->explanation }}</p>
        </div>
        @endif

        <div class="mt-6">
            <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Related Exams:</h3>
            @if($question->exams->count())
                <ul class="list-disc list-inside text-gray-900 dark:text-gray-100">
                    @foreach($question->exams as $exam)
                        <li>{{ $exam->title }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 dark:text-gray-400">No exams linked to this question.</p>
            @endif
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('admin.question-banks') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-800 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 transition">
            ← Back to Questions
        </a>
    </div>
</div>
@endsection
