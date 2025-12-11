@extends('layouts.admin.app')

@section('title', 'User Managements')

@section('content')

<!-- Success Notification -->
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

<!-- Error Notification -->
@if (session('error'))
<div x-data="{ show: true }" x-show="show"
     class="p-4 mb-4 bg-red-100 border-l-4 border-red-500 rounded-md shadow-sm"
     role="alert">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0a9 9 0 0118 0z"/>
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
<main >
<div >
    <div class="w-full p-3 bg-background-light dark:bg-background-dark sticky top-0 z-10 border-b border-gray-200 dark:border-gray-800">
        
<div class="flex items-center gap-4">
<div class="flex-1">
<label class="flex flex-col w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-10 gap-2">
    
    <input type="text" id="search" placeholder="Search questions..."
                class="flex-1 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                autocomplete="off">
    <select id="perPage" class="px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300 bg-white">
        <option value="10" {{ request('per_page', 50) == 10 ? 'selected' : '' }}>10 per page</option>
        <option value="25" {{ request('per_page', 50) == 25 ? 'selected' : '' }}>25 per page</option>
        <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 per page</option>
        <option value="100" {{ request('per_page', 50) == 100 ? 'selected' : '' }}>100 per page</option>
    </select>
    <a href="{{ route('admin.question-banks') }}"
            id="clearSearch" 
            class="px-3 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300 transition"
        >
            Clear
</a>
</div>
</label>
</div>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white dark:bg-gray-800 pl-4 pr-3 border border-gray-200 dark:border-gray-700" style="display: none;">
<p class="text-gray-800 dark:text-white text-sm font-medium leading-normal">Filter by Status</p>
<span class="material-symbols-outlined text-gray-600 dark:text-gray-400">arrow_drop_down</span>
</button>
<a href="{{ route('admin.question-banks.create') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
<span class="material-symbols-outlined">add</span>
<span class="truncate">Create New Question</span>
</a>
</div>

</div>
<div class="flex-1 p-3">
<div id="userTable" class="rounded-2xl bg-white  rounded-lg border border-gray-200 dark:border-gray-700">
        @include('admin.questions.table')
</div>

</main>
<script>
    const searchInput = document.getElementById('search');
    const perPageSelect = document.getElementById('perPage');
    let timeout = null;

    // Handle search
    searchInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            let search = this.value;
            let perPage = perPageSelect.value;

            fetch(`{{ route('admin.question-banks') }}?search=${encodeURIComponent(search)}&per_page=${perPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('userTable').innerHTML = html;
            })
            .catch(err => console.error(err));
        }, 300);
    });

    // Handle per_page change
    perPageSelect.addEventListener('change', function() {
        let search = searchInput.value;
        let perPage = this.value;

        fetch(`{{ route('admin.question-banks') }}?search=${encodeURIComponent(search)}&per_page=${perPage}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('userTable').innerHTML = html;
        })
        .catch(err => console.error(err));
    });

    // Handle pagination clicks dynamically
    document.addEventListener('click', function(e) {
        if (e.target.matches('#userTable .pagination a') || e.target.closest('#userTable .pagination a')) {
            e.preventDefault();
            let search = searchInput.value;
            let perPage = perPageSelect.value;
            let url = e.target.href || e.target.closest('a').href;
            
            // Append per_page to URL
            url += (url.includes('?') ? '&' : '?') + 'per_page=' + perPage;
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('userTable').innerHTML = html;
                document.querySelector('#userTable').scrollIntoView({ behavior: 'smooth' });
            });
        }
    });
</script>
@endsection
