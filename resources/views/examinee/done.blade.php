<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Vibtech Genesis - Exam Results</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark">
<div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<div class="flex flex-1 justify-center py-5">
<div class="layout-content-container flex flex-col max-w-[960px] flex-1">
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f0f2f4] dark:border-b-background-dark/20 px-10 py-3">
<div class="flex items-center gap-4 text-[#111418] dark:text-white">
<div class="size-6 text-primary">
<svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
</svg>
</div>
<h2 class="text-[#111418] dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Vibtech Genesis Examination Portal</h2>
</div>
<div class="flex flex-1 justify-end gap-8">

</div>
</header>
<main class="p-4 sm:p-10 flex-1 flex flex-col items-center">
<div class="w-full max-w-2xl bg-white dark:bg-background-dark/50 rounded-xl shadow-lg p-8 space-y-8">
<div class="flex flex-col items-center gap-4">

    @if ($status === 'passed')
    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-green-100 dark:bg-green-500/20">
        <span class="material-symbols-outlined text-5xl text-green-600 dark:text-green-400">check</span>
    </div>
    @else
    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20">
        <span class="material-symbols-outlined text-5xl text-red-600 dark:text-red-400">
        cancel
    </span>
    </div>
    @endif
<div class="text-center">
<p class="text-[#111418] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Exam Results</p>
@if ($status === 'passed')
    <p class="text-green-600 dark:text-green-400 text-lg font-medium leading-normal mt-2">
        🎉 Congratulations, you have passed!
    </p>
@else
    <p class="text-red-600 dark:text-red-400 text-lg font-medium leading-normal mt-2">
        ❌ You scored  <strong>{{ $userExam->scores }}</strong> %. Unfortunately, you did not reach the passing rate of <strong>{{ $userExam->exam->pass_mark }}</strong> %
    </p>
@endif
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
<div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-[#dbe0e6] dark:border-white/10 bg-background-light dark:bg-background-dark">
<p class="text-[#617589] dark:text-gray-400 text-base font-medium leading-normal">Total Questions</p>
<p class="text-[#111418] dark:text-white tracking-light text-2xl font-bold leading-tight">{{ $userExam->exam->questions }}</p>
</div>
<div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-[#dbe0e6] dark:border-white/10 bg-background-light dark:bg-background-dark">
<p class="text-[#617589] dark:text-gray-400 text-base font-medium leading-normal">Correct Answers</p>
<p class="text-[#111418] dark:text-white tracking-light text-2xl font-bold leading-tight">{{ $correctCount }}</p>
</div>
<div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-[#dbe0e6] dark:border-white/10 bg-background-light dark:bg-background-dark">
<p class="text-[#617589] dark:text-gray-400 text-base font-medium leading-normal">Score</p>
<p class="text-[#111418] dark:text-white tracking-light text-2xl font-bold leading-tight">{{ $userExam->scores }}%</p>
</div>
<div class="flex min-w-[158px] flex-1 flex-col col-span-1 sm:col-span-2 md:col-span-3 gap-2 rounded-lg p-6 border border-[#dbe0e6] dark:border-white/10 bg-background-light dark:bg-background-dark">
<p class="text-[#617589] dark:text-gray-400 text-base font-medium leading-normal">Attempts Used</p>
<p class="text-[#111418] dark:text-white tracking-light text-2xl font-bold leading-tight">{{ $userExam->attempts_used }} of 3</p>
</div>
</div>
@if ($status === 'passed')
    <p class="text-[#111418] dark:text-gray-300 text-base font-normal leading-normal pb-3 pt-1 px-4 text-center">
        You have successfully completed your certification. Please log out to secure your session.
    </p>
    <div class="flex px-4 py-3 justify-center">
        <button id="logoutBtn"
            class="flex w-full sm:w-auto min-w-[120px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-8 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
            <span class="truncate">Logout</span>
        </button>
    </div>
@elseif ($status === 'failed_max')
    {{-- 🚫 Failed after 3 attempts --}}
    <p class="text-red-600 dark:text-red-400 text-base font-normal leading-normal pb-3 pt-1 px-4 text-center">
        You have reached the maximum of 3 exam attempts and did not pass. Your account is temporarily locked from further exams.
    </p>
    <div class="flex px-4 py-3 justify-center">
        <button id="logoutBtn"
            class="flex w-full sm:w-auto min-w-[120px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-8 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
            <span class="truncate">Logout</span>
        </button>
    </div>

@else
    <p class="text-red-600 dark:text-red-400 text-base font-normal leading-normal pb-3 pt-1 px-4 text-center">
        You did not pass this time. Please review your materials and try again or log out to exit this session.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 px-4 py-3 justify-center">
        <button id="retakeBtn"
            class="flex w-full sm:w-auto min-w-[140px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-8 bg-green-600 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-green-700 transition-colors">
            <span class="truncate">Re-Take Exam</span>
        </button>

        <button id="logoutBtn"
            class="flex w-full sm:w-auto min-w-[120px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-8 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
            <span class="truncate">Logout</span>
        </button>
    </div>
@endif


</div>
</div>
</main>
</div>
</div>
</div>
</div>
</body>
<!-- Hidden logout form -->
<form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<script>
document.getElementById('logoutBtn').addEventListener('click', (e) => {
    e.preventDefault();
    if (confirm('Are you sure you want to log out?')) {
        document.getElementById('logoutForm').submit();
    }
});

const retakeBtn = document.getElementById('retakeBtn');
if (retakeBtn) {
    retakeBtn.addEventListener('click', () => {
        if (confirm('Do you want to retake the exam?')) {
            window.location.href = "{{ route('dashboard') }}"; // Update if your route differs
        }
    });
}
</script>
</html>