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
                                        @php
                                        // Tentukan KONDISI Anda di sini. 
                                        // Contoh: Tombol dinonaktifkan jika attempts_used sudah mencapai batas 3.
                                        $canAssignExam = ($user->attempts_used < 3); // Ganti dengan logika kondisi Anda
                                        
                                        // Tentukan URL tujuan (jika dinonaktifkan, set ke '#')
                                        $linkUrl = $canAssignExam 
                                            ? route('admin.users.assign-exam', ['id' => $user->id]) 
                                            : '#';
                                        
                                        // Tentukan class Tailwind untuk status disabled
                                        $disabledClasses = 'opacity-50 pointer-events-none cursor-not-allowed';
                                        
                                        // Tentukan class yang akan ditambahkan secara bersyarat
                                        $additionalClasses = $canAssignExam ? '' : $disabledClasses;

                                        // Ganti class hover:bg-blue-700 karena kita tidak ingin ada efek hover saat disabled
                                        $baseClasses = 'flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary dark:bg-primary-700 text-white dark:text-blue-200 text-sm font-bold leading-normal tracking-[0.015em]';
                                        
                                        // Gabungkan kelas dasar dan kelas bersyarat
                                        $finalClasses = $baseClasses . ' ' . $additionalClasses . ' ' . ($canAssignExam ? 'hover:bg-blue-700 dark:hover:bg-primary-600' : '');

                                    @endphp

                                    <a href="{{ $linkUrl }}" class="
                                        {{ $finalClasses }}
                                    ">
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
            @php $index++; 
                            $statusColors = [
                                'cancel' => 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-300',
                                'pending' => 'bg-orange-100 text-orange-700 dark:bg-orange-800 dark:text-orange-300',
                                'started' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                            ];
                        @endphp
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
                                                                @if($attempt->active_date)
                                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                                                    {{ $attempt->active_date->format('d-M-Y H:i')  ?? '-' }}
                                                                </span>
                                                                -
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                                                    {{ $attempt->end_date ? $attempt->end_date->format('d-M-Y H:i') : '-' }}
                                                                </span>
                                                                @else
                                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                                                    —
                                                                </span>
                                                                @endif
                                                            </p>
                                                        </td>
                                                        
                                                        <td class="px-6 py-4 font-medium text-green-600 dark:text-green-400">
                                                            {{ $attempt->attempts_used }}
                                                        </td>
                                                        <td class="px-6 py-4 font-medium text-green-600 dark:text-green-400 text-center">
                                                            {{ $attempt->data_score }}%
                                                            @php
                                                            // Tentukan kelas dasar untuk badge (ukuran, padding, rounded)
                                                            $baseClasses = 'px-3 py-1 text-xs font-semibold rounded-full uppercase tracking-wider';
                                                            
                                                            // Tentukan kelas warna berdasarkan status
                                                            $colorClasses = match ($attempt->data_status) {
                                                                'passed' => 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100',
                                                                'failed' => 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100',
                                                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100',
                                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100', // Default atau status tidak dikenal
                                                            };
                                                            
                                                            // Gabungkan semua kelas
                                                            $badgeClasses = $baseClasses . ' ' . $colorClasses;
                                                        @endphp

                                                        <p class="{{ $badgeClasses }}">
                                                            {{ $attempt->data_status }}
                                                        </p>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            @php
                                                                $start = $attempt->started_at;
                                                                $end = $attempt->finished_at;
                                                            @endphp

                                                            {{-- Cek apakah kedua nilai (start dan end) ada --}}
                                                            @if (isset($start) && isset($end))
                                                                @php
                                                                    // Karena ini adalah kolom timestamp dari Eloquent, mereka seharusnya sudah berupa objek Carbon.
                                                                    // Jika tidak, Anda mungkin perlu menggunakan Carbon::parse($start)
                                                                    $duration = $start->diff($end);
                                                                @endphp
                                                                
                                                                {{-- Format output durasi. Contoh: 1 jam 30 menit --}}
                                                                {{ $duration->format('%H Hours %I minutes %S seconds') }}
                                                            @elseif (isset($start) && !isset($end))
                                                                {{-- Jika ujian dimulai tapi belum selesai --}}
                                                                <span class="text-yellow-600 font-semibold">On Going</span>
                                                            @else
                                                                {{-- Jika ujian belum dimulai sama sekali --}}
                                                                <span class="text-gray-500">Not Started</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            {{ optional($attempt->finished_at)->format('d M Y') ?? '—' }}
                                                        </td>
                                                        <td class="px-6 py-4 text-right" x-data="{ openEdit: false, openDelete: false }">
                                                            @if($attempt->data_status==='pending' && $attempt->attempts_used ===0)
                                                            <!-- Edit Button -->
                                                            <button 
                                                                @click="openEdit = true"
                                                                class="inline-flex items-center px-2 py-1 text-blue-600 hover:text-blue-800 transition"
                                                                title="Edit Active & End Dates"
                                                            >
                                                                <x-heroicon-o-pencil-square class="w-5 h-5" />
                                                            </button>

                                                            <!-- Delete Button -->
                                                            <button 
                                                                @click="openDelete = true"
                                                                class="inline-flex items-center px-2 py-1 text-red-600 hover:text-red-800 transition"
                                                                title="Remove Exam Attempt"
                                                            >
                                                                <x-heroicon-o-trash class="w-5 h-5" />
                                                            </button>

                                                            <!-- ===== Edit Modal ===== -->
                                                            <div 
                                                                x-show="openEdit"
                                                                x-cloak
                                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                                            >
                                                                <div 
                                                                    @click.away="openEdit = false"
                                                                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-md p-6"
                                                                >
                                                                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                                                                        Edit Exam Dates — {{ $attempt->exam->title }}
                                                                    </h2>

                                                                    <form 
                                                                        action="{{ route('admin.users.update-exam', $attempt->id) }}" 
                                                                        method="POST"
                                                                    >
                                                                        @csrf
                                                                        @method('PUT')

                                                                        <div class="space-y-4">
                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                                                    Active Date
                                                                                </label>
                                                                                <input 
                                                                                    type="datetime-local" 
                                                                                    name="active_date"
                                                                                    value="{{ $attempt->active_date ? $attempt->active_date->format('Y-m-d\TH:i') : '' }}"
                                                                                    class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                                                                    required
                                                                                />
                                                                            </div>

                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                                                    End Date
                                                                                </label>
                                                                                <input 
                                                                                    type="datetime-local" 
                                                                                    name="end_date"
                                                                                    value="{{ $attempt->end_date ? $attempt->end_date->format('Y-m-d\TH:i') : '' }}"
                                                                                    class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                                                                    required
                                                                                />
                                                                            </div>
                                                                        </div>

                                                                        <div class="mt-6 flex justify-end gap-2">
                                                                            <button 
                                                                                type="button"
                                                                                @click="openEdit = false"
                                                                                class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600"
                                                                            >
                                                                                Cancel
                                                                            </button>
                                                                            <button 
                                                                                type="submit"
                                                                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                                                                            >
                                                                                Save Changes
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                            <!-- ===== Delete Confirmation Modal ===== -->
                                                            <div 
                                                                x-show="openDelete"
                                                                x-cloak
                                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                                            >
                                                                <div 
                                                                    @click.away="openDelete = false"
                                                                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-sm p-6"
                                                                >
                                                                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">
                                                                        Remove Exam Attempt
                                                                    </h2>
                                                                    <p class="text-gray-600 dark:text-gray-300 mb-5">
                                                                        Are you sure you want to remove <b>{{ $attempt->exam->title }}</b> from this user?
                                                                    </p>

                                                                    <form action="{{ route('admin.users.remove-exam', $attempt->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <div class="flex justify-end gap-2">
                                                                            <button 
                                                                                type="button"
                                                                                @click="openDelete = false"
                                                                                class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600"
                                                                            >
                                                                                Cancel
                                                                            </button>
                                                                            <button 
                                                                                type="submit"
                                                                                class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition"
                                                                            >
                                                                                Yes, Remove
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            @endif
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
                                                                                        #
                                                                                    </th>
                                                                                    <th class="px-2 py-2 font-medium">
                                                                                        Question
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
                                                                                    <td class="px-2 py-3">
                                                                                        {{ $index + 1 }} 
                                                                                    </td>
                                                                                    <td class="px-2 py-3">
                                                                                        {{ $question->question_stem }}
                                                                                    </td>
                                                                                    <td class="px-2 py-3 
                                        {{ optional($userAnswer)->
                                                                                        is_correct ? 'text-green-600' : 
                                        ($userAnswer ? 'text-red-600 dark:text-red-400' : 'text-gray-400') }}">
                                        @if($userAnswer)
                                            {{ $userAnswer->user_option }}
                                        @else
                                                                                        <em>
                                                                                            No answer
                                                                                        </em>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="px-2 py-3">
                                                                                        {{ $question->{'option_' . strtolower($question->correct_option)} ?? '-' }}
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