@extends('layouts.admin.app')

@section('title', 'User Managements')

@section('content')

<!-- ✅ Success Notification -->
@if (session('success'))
    <x-alert-message type="success">
        <ul class="list-disc list-inside space-y-1 text-sm">
            <li>{{ session('success') }}</li>
        </ul>
    </x-alert-message>
@endif

@if (session('error'))
    <x-alert-message type="error">
        <ul class="list-disc list-inside space-y-1 text-sm">
            <li>{{ session('error') }}</li>
        </ul>
    </x-alert-message>
@endif

<main>
    <div class="w-full p-3 bg-background-light dark:bg-background-dark sticky top-0 z-10 border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center gap-4">
            <!-- 🔍 Search -->
            <div class="flex-1">
                <label class="flex flex-col w-full">
                    <div class="flex w-full flex-1 items-stretch rounded-lg h-10 gap-2">
                        <input type="text" id="search" placeholder="Search exams..."
                            class="flex-1 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                            autocomplete="off"
                            value="{{ request('search', '') }}">
                        <select id="perPage" class="px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300 bg-white">
                            <option value="10" {{ request('per_page', 50) == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="25" {{ request('per_page', 50) == 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page', 50) == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                        <a href="{{ route('admin.exams') }}" id="clearSearch"
                            class="px-3 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                            Clear
                        </a>
                    </div>
                </label>
            </div>

            <!-- ➕ Create Button -->
            <a href="{{ route('admin.exams.create') }}"
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
                <span class="material-symbols-outlined">add</span>
                <span class="truncate">Create New Exam</span>
            </a>
        </div>
    </div>

    <div class="flex-1 p-3">
        <!-- 🧭 Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="examTabs" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="tab-btn inline-block p-4 border-b-2 rounded-t-lg {{ request('status', 'publish') === 'publish' ? 'active text-blue-600 border-blue-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300' }}"
                        data-status="publish" type="button">Published</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="tab-btn inline-block p-4 border-b-2 rounded-t-lg {{ request('status') === 'draft' ? 'active text-blue-600 border-blue-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300' }}"
                        data-status="draft" type="button">Draft</button>
                </li>
                <li class="me-2" role="presentation" style="display: none;">
                    <button class="tab-btn inline-block p-4 border-b-2 rounded-t-lg {{ request('status') === 'archived' ? 'active text-blue-600 border-blue-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300' }}"
                        data-status="archived" type="button">Archived</button>
                </li>
            </ul>
        </div>

        <!-- 🧾 Tables -->
        <div id="examTable" class="rounded-2xl bg-white rounded-lg border border-gray-200 dark:border-gray-700">
            @include('admin.exams.table')
        </div>
    </div>
</main>
@if(session('focus_tab') === 'draft')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const draftBtn = document.querySelector('.tab-btn[data-status="draft"]');
    if (draftBtn) draftBtn.click();
});
</script>
@endif

<script>
    const searchInput = document.getElementById('search');
    const perPageSelect = document.getElementById('perPage');
    const tabButtons = document.querySelectorAll('.tab-btn');
    let currentStatus = "{{ request('status', 'publish') }}";
    let timeout = null;

    // 🔄 Fetch exams based on tab + search + per_page
    function fetchExams() {
        const search = searchInput.value;
        const perPage = perPageSelect.value;

        fetch(`{{ route('admin.exams') }}?search=${encodeURIComponent(search)}&status=${encodeURIComponent(currentStatus)}&per_page=${perPage}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('examTable').innerHTML = html;
        })
        .catch(err => console.error(err));
    }

    // 🔍 Live search
    searchInput.addEventListener('keyup', () => {
        clearTimeout(timeout);
        timeout = setTimeout(fetchExams, 300);
    });

    // 📊 Per page change
    perPageSelect.addEventListener('change', fetchExams);

    // 🧭 Tab switch
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => {
                b.classList.remove('text-blue-600', 'border-blue-600', 'active');
                b.classList.add('text-gray-500');
            });
            btn.classList.add('text-blue-600', 'border-blue-600', 'active');
            btn.classList.remove('text-gray-500');
            currentStatus = btn.dataset.status;
            fetchExams();
        });
    });

    // 📄 Handle pagination dynamically
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('#examTable .pagination a');
        if (paginationLink) {
            e.preventDefault();
            const search = searchInput.value;
            const perPage = perPageSelect.value;
            let url = paginationLink.href;
            
            // Append status, search, and per_page to URL
            const separator = url.includes('?') ? '&' : '?';
            url += separator + 'status=' + currentStatus + '&per_page=' + perPage;
            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }
            
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                document.getElementById('examTable').innerHTML = html;
                document.querySelector('#examTable').scrollIntoView({ behavior: 'smooth' });
            });
        }
    });
</script>

@endsection
