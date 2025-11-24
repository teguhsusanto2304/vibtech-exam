@extends('layouts.admin.app')

@php
    // 1. Determine the mode (Create or Edit)
    $isEditMode = isset($exam) && $exam->id;
    $pageTitle = $isEditMode ? 'Edit Exam: ' . ($exam->title ?? 'Untitled') : 'Create New Exam';
    $formAction = $isEditMode ? route('admin.exams.update', $exam->id) : route('admin.exams.store');
    $formMethod = $isEditMode ? 'PUT' : 'POST';

    // Helper to fetch the correct value: old input > existing exam data > default
    $getFieldValue = function ($field, $default = '') use ($isEditMode, $exam) {
        return old($field, $isEditMode ? ($exam->$field ?? $default) : $default);
    };

    // Helper to fetch the correct value for number fields (defaults to 0)
    $getNumberValue = function ($field) use ($isEditMode, $exam) {
        return old($field, $isEditMode ? ($exam->$field ?? 0) : 0);
    };

    // Helper to determine if a toggle/checkbox should be checked (defaults to 1/checked for new exams)
    $getCheckboxChecked = function ($field, $default = 1) use ($isEditMode, $exam) {
        $value = old($field, $isEditMode ? ($exam->$field ?? $default) : $default);
        return $value == 1 ? 'checked' : '';
    };

@endphp

@section('title', $pageTitle)

@section('content')
@if ($errors->any())
<x-alert-message type="error">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach ($errors->all() as $error)
        <li>
            {{ $error }}
        </li>
        @endforeach
    </ul>
</x-alert>
@endif
<form action="{{ $formAction }}" method="post">
    @csrf
    {{-- 2. Spoof the PUT/PATCH method for updates --}}
    @if ($isEditMode)
        @method('PUT')
    @endif

    <div class="bg-white dark:bg-background-dark/50 rounded-xl mt-8 p-6 shadow-sm">
        <h2 class="text-[#111418] dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] pb-3 pt-2">
            {{ $isEditMode ? 'Edit Exam Details' : 'Exam Details' }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div class="flex flex-col">
                <label class="flex flex-col min-w-40 flex-1">
                    <p class="text-[#111418] dark:text-white text-base font-medium leading-normal pb-2">
                        Exam Title
                    </p>
                    <div class="flex w-full flex-1 items-stretch rounded-lg">
                        <input 
                            name="title" 
                            id="title" 
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border border-[#dbe0e6] dark:border-gray-600 bg-white dark:bg-gray-800 focus:border-primary h-14 placeholder:text-[#617589] p-[15px] text-base font-normal leading-normal" 
                            placeholder="e.g. Certified Vibtech Analyst" 
                            {{-- 3. Use the helper function to pre-fill the value --}}
                            value="{{ $getFieldValue('title') }}"
                            required
                        />
                    </div>
                </label>
            </div>
            <div class="flex flex-col">
                <label class="flex flex-col min-w-40 flex-1">
                    <p class="text-[#111418] dark:text-white text-base font-medium leading-normal pb-2">
                        Exam Description
                    </p>
                    <textarea
                        id="description"
                        name="description"
                        class="form-input flex w-full min-w-0 flex-1 resize-y overflow-y-auto rounded-lg text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border border-[#dbe0e6] dark:border-gray-600 bg-white dark:bg-gray-800 focus:border-primary min-h-14 p-[15px] text-base font-normal leading-normal"
                        placeholder="A brief summary of the exam"
                    >{{ $getFieldValue('description') }}</textarea>

                    

                </label>
            </div>
        </div>
        <div class="mt-6">
            <label class="block text-base font-medium text-[#111418] dark:text-white pb-2">
                Exam Instructions
            </label>
            <textarea 
                name="instruction" 
                id="ckeditor" 
                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border border-[#dbe0e6] dark:border-gray-600 bg-white dark:bg-gray-800 focus:border-primary min-h-14 h-14 placeholder:text-[#617589] p-[15px] text-base font-normal leading-normal" 
                placeholder="Detailed instructions for the exam takers"
            >{{ $getFieldValue('instruction') }}</textarea>
        </div>

        <h2 class="text-[#111418] dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-0 pb-3 pt-8">
            Exam Settings
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
            <div class="flex flex-col">
                <label class="flex flex-col min-w-40 flex-1">
                    <p class="text-[#111418] dark:text-white text-base font-medium leading-normal pb-2">
                        Number of Questions
                    </p>
                    <input 
                        name="questions" 
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border border-[#dbe0e6] dark:border-gray-600 bg-white dark:bg-gray-800 focus:border-primary h-14 placeholder:text-[#617589] p-[15px] text-base font-normal leading-normal" 
                        max="300" 
                        min="1" 
                        type="number" 
                        value="{{ $getNumberValue('questions') }}"
                    />
                </label>
            </div>
            <div class="flex flex-col">
                <label class="flex flex-col min-w-40 flex-1">
                    <p class="text-[#111418] dark:text-white text-base font-medium leading-normal pb-2">
                        Duration (in minutes)
                    </p>
                    <input 
                        name="duration" 
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border border-[#dbe0e6] dark:border-gray-600 bg-white dark:bg-gray-800 focus:border-primary h-14 placeholder:text-[#617589] p-[15px] text-base font-normal leading-normal" 
                        type="number" 
                        value="{{ $getNumberValue('duration') }}"
                    />
                </label>
            </div>
            <div class="flex flex-col">
                <label class="flex flex-col min-w-40 flex-1">
                    <p class="text-[#111418] dark:text-white text-base font-medium leading-normal pb-2">
                        Pass Mark (%)
                    </p>
                    <input 
                        name="pass_mark" 
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border border-[#dbe0e6] dark:border-gray-600 bg-white dark:bg-gray-800 focus:border-primary h-14 placeholder:text-[#617589] p-[15px] text-base font-normal leading-normal" 
                        type="number" 
                        value="{{ $getNumberValue('pass_mark') }}"
                        max="100"
                        required
                    />
                </label>
            </div>
        </div>
        <h2 class="text-[#111418] dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-0 pb-3 pt-8">
            Exam Rules
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            {{-- Randomize Questions --}}
            <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                <div class="flex flex-col">
                    <p class="text-[#111418] dark:text-white font-medium">
                        Randomize Questions
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Present questions in a different order.
                    </p>
                </div>
                <input type="hidden" name="randomize_questions" value="0">
                <label class="relative inline-flex items-center cursor-pointer" for="randomize-questions">
                    <input 
                        type="checkbox"
                        class="sr-only peer"
                        id="randomize-questions"
                        name="randomize_questions"
                        value="1"
                        {{-- 4. Check status based on old data or exam data (default 1/checked) --}}
                        {{ $getCheckboxChecked('randomize_questions', 1) }}
                    >
                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-focus:ring-2 peer-focus:ring-primary/50 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>
            {{-- Randomize Options --}}
            <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                <div class="flex flex-col">
                    <p class="text-[#111418] dark:text-white font-medium">
                        Randomize Options
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Present answers in a different order.
                    </p>
                </div>
                <input type="hidden" id="data_status" name="data_status" value="{{ $getFieldValue('data_status') }}">
                <input type="hidden" name="randomize_options" value="0">
                <label class="relative inline-flex items-center cursor-pointer" for="randomize-options">
                    <input 
                        class="sr-only peer" 
                        id="randomize-options" 
                        name="randomize_options" 
                        type="checkbox" 
                        value="1"
                        {{-- 4. Check status based on old data or exam data (default 1/checked) --}}
                        {{ $getCheckboxChecked('randomize_options', 1) }}
                    />
                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-focus:ring-2 peer-focus:ring-primary/50 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                    </div>
                </label>
            </div>
        </div>
        <div class="pt-6 flex justify-end gap-3 border-t mt-6">
            <a href="{{ route('admin.exams') }}" 
               class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                Cancel
            </a>
            {{-- Publish button --}}
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                onclick="setStatus('publish')">
                {{ $isEditMode ? 'Update & Publish' : 'Publish Exam' }}
            </button>

            {{-- Draft button --}}
            <button type="submit"
                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition"
                onclick="setStatus('draft')">
                {{-- 5. Dynamic button text --}}
                {{ $isEditMode ? 'Save Changes' : 'Save As Draft' }}
            </button>
        </div>
    </div>
</form>

<script>
    function setStatus(status) {
        document.getElementById('data_status').value = status;
    }
</script>

<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#ckeditor' ),{
            ckfinder: {
                uploadUrl: '{{ route('ckeditor.upload').'?_token='.csrf_token()}}',
            }
        })
        .catch( error => {
            console.error(error);
        } );
    ClassicEditor
        .create( document.querySelector( '#description' ),{
            ckfinder: {
                uploadUrl: '{{ route('ckeditor.upload').'?_token='.csrf_token()}}',
            }
        })
        .catch( error => {
            console.error(error);
        } );
</script>


@endsection
