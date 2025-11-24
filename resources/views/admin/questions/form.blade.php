@extends('layouts.admin.app')

@section('title', 'User Management')

@section('content')
@if ($errors->any())
    <x-alert-message type="error">
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
<form action="{{ isset($question) ? route('admin.question-banks.update', ['id'=>$question->id]) : route('admin.question-banks.store') }}"
      method="POST" enctype="multipart/form-data"
      class="space-y-6 bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
    @csrf
    @if(isset($question))
        @method('PUT')
    @endif

    <!-- Question Stem -->
    <div>
        <label for="question_stem" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Question Stem
        </label>
        <textarea name="question_stem" id="question_stem" rows="3"
            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500"
            required>{{ old('question_stem', $question->question_stem ?? '') }}</textarea>
    </div>

    <!-- Question Image (Optional) -->
    <div>
        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Upload Image (Optional)
        </label>

        <input type="file" name="image" id="image"
            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500">

        @if(isset($question) && $question->image)
            <div class="mt-3">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Current Image:</p>
                <img src="{{ asset('storage/question-images/' . $question->image) }}" class="w-32 h-auto rounded-md border" />
            </div>
        @endif
    </div>


    <!-- Topic -->
    <div style="display: none;">
        <label for="topic" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Topic
        </label>
        <input type="text" name="topic" id="topic"
            value="{{ old('topic', $question->topic ?? '') }}"
            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500"
            >
    </div>

    <!-- Difficulty Level -->
    <div style="display: none;">
        <label for="difficulty_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Difficulty Level
        </label>
        <select name="difficulty_level" id="difficulty_level"
            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select Difficulty</option>
            <option value="1" {{ old('difficulty_level', $question->difficulty_level ?? '') == '1' ? 'selected' : '' }}>Easy</option>
            <option value="3" {{ old('difficulty_level', $question->difficulty_level ?? '') == '2' ? 'selected' : '' }}>Medium</option>
            <option value="3" {{ old('difficulty_level', $question->difficulty_level ?? '') == '3' ? 'selected' : '' }}>Hard</option>
            <option value="4" {{ old('difficulty_level', $question->difficulty_level ?? '') == '4' ? 'selected' : '' }}>Adaptive</option>
        </select>
    </div>

    <!-- Options -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach (['A', 'B', 'C', 'D'] as $option)
            <div>
                <label for="option_{{ strtolower($option) }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Option {{ $option }}
                </label>
                <input type="text" name="option_{{ strtolower($option) }}" id="option_{{ strtolower($option) }}"
                    value="{{ old('option_' . strtolower($option), $question->{'option_' . strtolower($option)} ?? '') }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>
        @endforeach
    </div>

    <!-- Correct Option -->
    <div>
        <label for="correct_option" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Correct Option
        </label>
        <select name="correct_option" id="correct_option"
            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500"
            required>
            <option value="">Select Correct Option</option>
            @foreach (['A', 'B', 'C', 'D'] as $option)
                <option value="{{ $option }}" {{ old('correct_option', $question->correct_option ?? '') == $option ? 'selected' : '' }}>
                    Option {{ $option }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Explanation -->
    <div>
        <label for="explanation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Explanation (Optional)
        </label>
        <textarea name="explanation" id="explanation" rows="3"
            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500">{{ old('explanation', $question->explanation ?? '') }}</textarea>
    </div>

    <!-- Buttons -->
    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.question-banks') }}"
           class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
            Cancel
        </a>
        <button type="submit"
            class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
            {{ isset($question) ? 'Update Question' : 'Create Question' }}
        </button>
    </div>
</form>

@endsection
