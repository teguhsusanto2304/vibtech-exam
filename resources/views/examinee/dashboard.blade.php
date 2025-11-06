<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Vibtech Genesis Examination Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#dbe0e6] dark:border-b-gray-700 px-4 sm:px-10 py-4 bg-white dark:bg-background-dark">
                <div class="flex items-center gap-4 text-gray-800 dark:text-white">
                    <div class="size-6 text-primary">
                        <!-- Logo SVG -->
                        <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_6_330)">
                                <path clip-rule="evenodd" d="M24 0.757355L47.2426 24L24 47.2426L0.757355 24L24 0.757355ZM21 35.7574V12.2426L9.24264 24L21 35.7574Z" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                            <defs>
                                <clippath id="clip0_6_330">
                                    <rect fill="white" height="48" width="48"></rect>
                                </clippath>
                            </defs>
                        </svg>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold tracking-tight text-gray-800 dark:text-white">Vibtech Genesis Examination Portal</h2>
                </div>
                <button class="flex items-center justify-center rounded-full h-10 w-10 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                    <span class="material-symbols-outlined">person</span>
                </button>
            </header>
            <main class="flex flex-1 justify-center py-10 px-4 sm:px-6 lg:px-8">
                <div class="layout-content-container flex flex-col w-full max-w-2xl">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 md:p-8">
                        <div class="flex flex-wrap justify-between gap-4 mb-6">
                            <h1 class="text-3xl font-black tracking-tight text-primary dark:text-blue-300">{{ $arrExam['title'] }}</h1>
                        </div>
                        
                        <!-- Exam Details Card -->
                        <div class="mb-8 p-4 border-l-4 border-primary/30 dark:border-blue-500/50 bg-primary/10 dark:bg-blue-900/20 rounded-lg">
                            <div class="grid grid-cols-1 sm:grid-cols-[150px_1fr] gap-x-6 gap-y-4">
                                <div class="col-span-2 sm:grid sm:grid-cols-subgrid border-b border-b-gray-300 dark:border-b-gray-600 py-4">
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-normal">Total Questions</p>
                                    <p class="text-gray-900 dark:text-white text-base font-semibold">{{ $arrExam['questions'] }}</p>
                                </div>
                                <div class="col-span-2 sm:grid sm:grid-cols-subgrid border-b border-b-gray-300 dark:border-b-gray-600 py-4">
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-normal">Time Allotment</p>
                                    <p class="text-gray-900 dark:text-white text-base font-semibold">{{ $arrExam['duration'] }} Minutes</p>
                                </div>
                                <div class="col-span-2 sm:grid sm:grid-cols-subgrid pt-4">
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-normal">Attempts Used</p>
                                    <p class="text-gray-900 dark:text-white text-base font-semibold">{{ $arrExam['attempt_used'] }} of 3</p>
                                </div>
                            </div>
                        </div>

                        <!-- Start Exam Button -->
                        <div class="flex flex-col items-center">
                            <div x-data="{ open: false }" class="w-full flex flex-col items-center">
                                <button id="startExamBtn" class="w-full max-w-xs flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-lg font-bold tracking-wider hover:bg-blue-800 transition-colors duration-300 shadow-md hover:shadow-lg">
                                    <span>Start Exam</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="open" id="confirmationModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black bg-opacity-60 transition-opacity duration-300" aria-modal="true" role="dialog" aria-labelledby="modalTitle">
        
        <!-- Modal Backdrop (Click to close) -->
        <div id="modalBackdrop" class="absolute inset-0"></div>

        <!-- Modal Content Container -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-lg z-10 transform transition-all duration-300 scale-100 opacity-100">
            
            <!-- Body -->
            <div class="p-2 space-y-2">
                <div class="text-center">
<span class="material-symbols-outlined text-primary text-5xl">task_alt</span>
<h1 class="text-3xl font-black text-[#111418] dark:text-white mt-4">Exam Rules &amp; Instructions</h1>
<p class="text-[#617589] dark:text-gray-400 text-base mt-2">Please read the following rules carefully before you begin.</p>
</div>
            {!! $arrExam['instruction'] !!}

            <!-- Footer (Action Buttons) -->
            <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                <button id="cancelBtn" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-colors duration-200">
                    Cancel
                </button>
                <button id="confirmStartBtn" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white font-semibold hover:bg-blue-800 transition-colors duration-200 shadow-md">
                    Yes, Start Now
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal Interaction -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const startExamBtn = document.getElementById('startExamBtn');
            const confirmationModal = document.getElementById('confirmationModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const confirmStartBtn = document.getElementById('confirmStartBtn');
            const modalBackdrop = document.getElementById('modalBackdrop');

            // Function to show the modal
            const showModal = () => {
                confirmationModal.classList.remove('hidden');
                confirmationModal.classList.add('flex');
            };

            // Function to hide the modal
            const hideModal = () => {
                confirmationModal.classList.add('hidden');
                confirmationModal.classList.remove('flex');
            };

            // 1. Show modal when Start Exam is clicked
            startExamBtn.addEventListener('click', showModal);

            // 2. Hide modal when Cancel button is clicked
            cancelBtn.addEventListener('click', hideModal);

            // 3. Hide modal when close icon is clicked
           
            // 4. Hide modal when backdrop is clicked
            modalBackdrop.addEventListener('click', hideModal);
            
            // 5. Handle the confirmation action
            confirmStartBtn.addEventListener('click', () => {
                //hideModal();
                window.location.href = "{{ route('exam',['examId'=>$arrExam['examId']]) }}";
                // In a real application, you would navigate to the exam page here.
                console.log("Exam confirmed and starting...");
                // Example of where navigation or API call would go:
                // window.location.href = '/start-exam'; 
            });
        });
    </script>
</body>
</html>
