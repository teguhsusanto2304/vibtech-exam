@extends('layouts.examinee.app')

@section('title', $arrExam['title'])

@section('content')
<main class="flex flex-1 justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="layout-content-container flex flex-col w-full max-w-2xl">
        <div class="bg-white shadow-xl rounded-xl p-6 md:p-8">
            <div class="flex flex-wrap justify-between gap-4 mb-6">
                <h1 class="text-3xl font-black tracking-tight text-primary">{{ $arrExam['title'] }}</h1>
            </div>
            <div class="flex flex-wrap justify-between gap-4 mb-6">
                <h2 class="text-1xl text-primary">{{ $arrExam['description'] }}</h2>
            </div>

            <!-- Exam Details Card -->
            <div class="mb-8 p-4 border-l-4 border-primary/30 dark:border-white-500/50 bg-primary/10 dark:bg-white-900/20 rounded-lg">
                <div class="grid grid-cols-1 sm:grid-cols-[150px_1fr] gap-x-6 gap-y-4">
                    <div class="col-span-2 sm:grid sm:grid-cols-subgrid border-b border-b-gray-300 dark:border-b-gray-600 py-4">
                        <p class="text-gray-500  text-sm font-normal">Total Questions</p>
                        <p class="text-gray-900  text-base font-semibold">{{ $arrExam['questions'] }}</p>
                    </div>
                    <div class="col-span-2 sm:grid sm:grid-cols-subgrid border-b border-b-gray-300 dark:border-b-gray-600 py-4">
                        <p class="text-gray-500  text-sm font-normal">Time Allotment</p>
                        <p class="text-gray-900  text-base font-semibold">{{ $arrExam['duration'] }} Minutes</p>
                    </div>
                    <div class="col-span-2 sm:grid sm:grid-cols-subgrid border-b border-b-gray-300 dark:border-b-gray-600 py-4">
                        <p class="text-gray-500  text-sm font-normal">Passing Rate</p>
                        <p class="text-gray-900  text-base font-semibold">{{ $arrExam['pass_mark'] }} %</p>
                    </div>
                    <div class="col-span-2 sm:grid sm:grid-cols-subgrid pt-4">
                        <p class="text-gray-500  text-sm font-normal">Attempts Used</p>
                        <p class="text-gray-900  text-base font-semibold">{{ $arrExam['attempt_used'] }} of 3</p>
                    </div>
                </div>
            </div>

            <!-- Start Exam Button -->
            @if((int) $arrExam['attempt_used'] < 3)
            <div class="flex flex-col items-center">
                <button id="startExamBtn"
                    class="w-full max-w-xs flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-lg font-bold tracking-wider hover:bg-blue-800 transition-colors duration-300 shadow-md hover:shadow-lg">
                    <span>Start Exam</span>
                </button>
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Confirmation Modal -->
<div id="confirmationModal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black bg-opacity-60 transition-opacity duration-300"
    aria-modal="true" role="dialog" aria-labelledby="modalTitle">

    <!-- Modal Backdrop (Click to close) -->
    <div id="modalBackdrop" class="absolute inset-0"></div>

    <!-- Modal Content -->
    <div
        class="bg-white  rounded-xl shadow-2xl w-full max-w-lg z-10 transform transition-all duration-300 scale-100 opacity-100 max-h-[90vh] flex flex-col">
        
        <!-- Header -->
        <div class="p-4 text-center border-b border-gray-200 ">
            <span class="material-symbols-outlined text-primary text-5xl">task_alt</span>
            <h1 class="text-3xl font-black text-[#111418]  mt-4">Exam Rules & Instructions</h1>
            <p class="text-[#617589] text-base mt-2">Please read the rules carefully before starting.</p>
        </div>

        <!-- Scrollable Body -->
        <div class="p-4 space-y-2 overflow-y-auto flex-1">
            {!! $arrExam['instruction'] !!}
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
            <button id="cancelBtn"
                class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-colors duration-200">
                Cancel
            </button>
            <button id="confirmStartBtn"
                class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white font-semibold hover:bg-blue-800 transition-colors duration-200 shadow-md">
                Yes, Start Now
            </button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const startExamBtn = document.getElementById('startExamBtn');
        const confirmationModal = document.getElementById('confirmationModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmStartBtn = document.getElementById('confirmStartBtn');
        const modalBackdrop = document.getElementById('modalBackdrop');

        // Show the modal
        const showModal = () => {
            confirmationModal.classList.remove('hidden');
            confirmationModal.classList.add('flex');
        };

        // Hide the modal
        const hideModal = () => {
            confirmationModal.classList.add('hidden');
            confirmationModal.classList.remove('flex');
        };

        // Show modal when Start Exam is clicked
        startExamBtn?.addEventListener('click', showModal);

        // Hide modal when Cancel or Backdrop is clicked
        cancelBtn?.addEventListener('click', hideModal);
        modalBackdrop?.addEventListener('click', hideModal);

        // Confirm to start exam
        confirmStartBtn?.addEventListener('click', () => {
            window.location.href = "{{ route('start-exam', ['examId' => $arrExam['examId']]) }}";
        });
    });
</script>
@endsection
