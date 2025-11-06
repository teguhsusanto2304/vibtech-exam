@extends('layouts.admin.app')

@section('title', 'Exam Questions')

@section('content')
@php
    if($exam->examQuestions->count() == $exam->questions)
        {
            $disabled = 'disabled';
        } else {
            $disabled = '';
        }
@endphp
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
                class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-red-500 rounded-lg p-1.5 hover:bg-red-200"
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
        <h1 class="text-[#343A40] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em] min-w-72">{{ $exam->title }}</h1>

        <div class="inline-flex rounded-lg shadow-md" role="group">
        @if($exam->data_status=='draft')
            {{-- PENDING BUTTON (Requires Confirmation) --}}
            <form action="{{ route('admin.exams.update-status', ['id'=>$exam->id]) }}" 
                  method="POST" 
                  style="display: inline;" 
                  class="needs-confirmation" 
                  data-action="set the exam status to PENDING">
                @csrf
                @method('PUT')
                <input type="hidden" name="data_status" value="pending">
                
                <button type="submit" 
                        class="flex items-center justify-center h-10 px-4 
                               bg-red-500 hover:bg-red-600 
                               dark:bg-red-700 dark:hover:bg-red-600 
                               text-white text-sm font-bold 
                               border-r border-red-600 dark:border-red-800 rounded-l-lg">
                    <span class="truncate">Pending</span>
                </button>
            </form>

            {{-- PUBLISH BUTTON (Requires Confirmation) --}}
            <form action="{{ route('admin.exams.update-status', ['id'=>$exam->id]) }}" 
                  method="POST" 
                  style="display: inline;" 
                  class="needs-confirmation" 
                  data-action="PUBLISH this exam immediately">
                @csrf
                @method('PUT')
                <input type="hidden" name="data_status" value="publish">
                
                <button type="submit" 
                        class="flex items-center justify-center h-10 px-4 
                               bg-green-500 hover:bg-green-600 
                               dark:bg-green-700 dark:hover:bg-green-600 
                               text-white text-sm font-bold 
                               border-r border-green-600 dark:border-green-800">
                    <span class="truncate">Publish</span>
                </button>
            </form>
        @else
            {{-- DRAFT BUTTON (Requires Confirmation) --}}
            <form action="{{ route('admin.exams.update-status', ['id'=>$exam->id]) }}" 
                  method="POST" 
                  style="display: inline;" 
                  class="needs-confirmation" 
                  data-action="revert the exam status to DRAFT">
                @csrf
                @method('PUT')
                <input type="hidden" name="data_status" value="draft">
                
                <button type="submit" 
                        class="flex items-center justify-center h-10 px-4 
                               bg-yellow-500 hover:bg-yellow-600 
                               dark:bg-yellow-700 dark:hover:bg-yellow-600 
                               text-white text-sm font-bold 
                               border-r border-yellow-600 dark:border-yellow-800 rounded-l-lg">
                    <span class="truncate">Draft</span>
                </button>
            </form>
        @endif
            <a href="{{ route('admin.exams') }}" 
                class="flex items-center justify-center h-10 px-4 
                       bg-gray-500 hover:bg-gray-600 
                       dark:bg-gray-700 dark:hover:bg-gray-600 
                       text-white text-sm font-bold 
                       rounded-r-lg"> <span class="truncate">Back to All Exams</span>
            </a>
            
        </div>
    </div>
    
    <!-- User Profile Card -->
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
                <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $exam->pass_mark  }} %</p>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Status</p>
                <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{!! $exam->data_status_badge  !!}</p>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <form action="{{ route('admin.exams.assign-questions', ['id' => $exam->id]) }}" method="POST">
        @csrf
        <!-- Left Panel: Question Bank -->
        <div class="flex flex-col bg-content-light dark:bg-content-dark rounded-xl border border-border-light dark:border-border-dark">
            <div class="p-4 border-b border-border-light dark:border-border-dark">
                <h2 class="text-text-light dark:text-text-dark text-lg font-bold">Question Bank</h2>
                <p class="text-subtle-light dark:text-subtle-dark text-sm">Select questions to add to the exam.</p>
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
                            <input 
                                class="form-checkbox rounded text-primary focus:ring-primary" 
                                type="checkbox" 
                                name="questions[]" 
                                value="{{ $question->id }}"
                                {{ $isChecked ? 'checked' : '' }}
                            />
                            <p class="text-sm">{{ $question->question_stem }}</p>
                        </div>
                        <button 
                            type="button"
                            class="p-1 rounded-full text-subtle-light dark:text-subtle-dark hover:bg-primary/20 hover:text-primary transition-colors"
                        >
                            <span class="material-symbols-outlined text-lg" style="display: none;">add_circle</span>
                        </button>
                    </div>
                @empty
                    <p class="p-3 text-subtle-light dark:text-subtle-dark">No questions available.</p>
                @endforelse
            </div>

            <!-- Bulk Action Footer -->
            <div class="p-4 border-t border-border-light dark:border-border-dark mt-auto">
                <button {{ $disabled }}
                    class="w-full text-center px-4 py-2 text-sm font-semibold text-white rounded-lg bg-primary hover:bg-primary/90 transition-colors disabled:bg-gray-300 dark:disabled:bg-gray-600"
                >
                    Add Selected
                </button>
            </div>
        </div>
        </form>

        <!-- Right Panel: Assigned Questions -->
        <div class="flex flex-col bg-content-light dark:bg-content-dark rounded-xl border border-border-light dark:border-border-dark">
            <div class="p-4 border-b border-border-light dark:border-border-dark flex justify-between items-center">
                <div>
                    <h2 class="text-text-light dark:text-text-dark text-lg font-bold">Questions in Exam</h2>
                    <p class="text-subtle-light dark:text-subtle-dark text-sm">
                        Currently {{ $exam->examQuestions->count() }} of {{ $exam->questions }} questions assigned.
                    </p>
                </div>
                @if($exam->examQuestions->count() > 0)
                {{-- CLEAR ALL BUTTON (Requires Confirmation) --}}
                <form action="{{ route('admin.exams.clear-questions', $exam->id) }}" 
                      method="POST" 
                      class="needs-confirmation" 
                      data-action="CLEAR ALL assigned questions (This cannot be undone)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 text-sm font-semibold hover:underline">Clear All</button>
                </form>
                @endif
            </div>

            @if($exam->examQuestions->count() > 0)
                <!-- Assigned Question List -->
                <div class="p-2 space-y-2 overflow-y-auto" style="max-height: 500px;">
                    @foreach($exam->examQuestions as $index => $question)
                        <div class="flex items-center justify-between p-3 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark">
                            <div class="flex items-center gap-3">
                                <button class="cursor-grab text-subtle-light dark:text-subtle-dark">
                                    <span class="material-symbols-outlined">drag_indicator</span>
                                </button>
                                <span class="font-semibold text-sm w-6">{{ $index + 1 }}.</span>
                                <p class="text-sm">{{ $question->question_stem }}</p>
                            </div>
                            
                            {{-- REMOVE SINGLE QUESTION BUTTON (Requires Confirmation) --}}
                            <form action="{{ route('admin.exams.remove-question', ['examId'=>$exam->id, 'questionId'=>$question->id]) }}" 
                                  method="POST" 
                                  class="needs-confirmation" 
                                  data-action="REMOVE question #{{ $index + 1 }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 rounded-full text-subtle-light dark:text-subtle-dark hover:bg-red-500/20 hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined text-lg">remove_circle</span>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="p-4 flex-grow flex flex-col items-center justify-center text-center">
                    <span class="material-symbols-outlined text-6xl text-subtle-light/50 dark:text-subtle-dark/50">quiz</span>
                    <h3 class="font-bold mt-4">This exam has no questions yet</h3>
                    <p class="text-sm text-subtle-light dark:text-subtle-dark max-w-xs mt-1">
                        Add questions from the Question Bank on the left to get started.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

{{-- 
    CUSTOM CONFIRMATION MODAL - Move this into a dedicated component 
    or include it at the very bottom of your layout file. 
--}}
<div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" aria-modal="true" role="dialog">
    
    <!-- Backdrop -->
    <div id="modal-backdrop" class="absolute inset-0 bg-gray-900/50 dark:bg-gray-900/80 transition-opacity"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm mx-auto p-6 transform transition-all scale-95 opacity-0 duration-200">
        <div class="text-center">
            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">Confirm Action</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    You are about to <strong id="modal-action-text">perform an irreversible action</strong>. 
                    Please confirm to proceed.
                </p>
            </div>
        </div>
        
        <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
            <button type="button" id="confirm-action" 
                    class="inline-flex w-full justify-center rounded-lg border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm">
                Confirm
            </button>
            <button type="button" id="cancel-action"
                    class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('confirm-modal');
        const confirmButton = document.getElementById('confirm-action');
        const cancelButton = document.getElementById('cancel-action');
        const actionTextElement = document.getElementById('modal-action-text');
        
        // Select all forms that require confirmation
        const formsNeedingConfirmation = document.querySelectorAll('.needs-confirmation');
        
        let formToSubmit = null; // Variable to hold the form element that was clicked

        // --- Modal Control Functions ---
        const showModal = (actionMessage) => {
            // Update the dynamic text based on the form's data-action attribute
            actionTextElement.textContent = actionMessage;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Animate the content
            setTimeout(() => {
                const content = modal.querySelector('.relative');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        };

        const hideModal = () => {
            const content = modal.querySelector('.relative');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                formToSubmit = null; // Clear the form reference
            }, 200);
        };

        // --- Attach Listeners to ALL Forms ---
        formsNeedingConfirmation.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                // 1. Store the reference to the currently submitting form
                formToSubmit = this;
                
                // 2. Get the confirmation message from the form's data attribute
                const actionMessage = this.getAttribute('data-action') || 'perform this action';
                
                // 3. Show the modal with the custom message
                showModal(actionMessage);
            });
        });

        // --- Modal Action Handlers ---

        // 1. Handle Confirm Button Click
        confirmButton.addEventListener('click', () => {
            if (formToSubmit) {
                // Programmatically submit the stored form
                formToSubmit.submit();
                
                // Hide the modal 
                hideModal();
            }
        });

        // 2. Handle Cancel/Close Button Clicks
        cancelButton.addEventListener('click', hideModal);
        document.getElementById('modal-backdrop').addEventListener('click', hideModal);
    });
</script>
