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

            <!-- TEXT SEARCH -->
            <input type="text" id="search" placeholder="Search users..."
                class="w-1/2 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                autocomplete="off">

            <!-- SELECT FILTER -->
            <select id="filterByElement"
                class="px-3 py-2 border rounded-lg bg-white focus:ring focus:ring-blue-300">
                <option value="">Choose Ones</option>
                <option value="name">Name</option>
                <option value="company">Company</option>
                <option value="email">Email</option>
            </select>

            <!-- CLEAR BUTTON -->
            <a href="{{ route('admin.users') }}" id="clearSearch"
                class="ml-2 px-3 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Clear
            </a>

        </div>
    </label>
</div>


            <!-- ➕ Create Button -->
            <a href="{{ route('admin.users.create') }}"
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
                <span class="material-symbols-outlined">add</span>
                <span class="truncate">Create New Account</span>
            </a>
        </div>
    </div>

    <div class="flex-1 p-3">
        <!-- 🧭 Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="userTabs" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="tab-btn inline-block p-4 border-b-2 rounded-t-lg active text-blue-600 border-blue-600"
                        data-role="admin" data-status="active" type="button">Admin Account (Active)</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="tab-btn inline-block p-4 border-b-2 rounded-t-lg text-gray-500 hover:text-gray-600 hover:border-gray-300"
                        data-role="admin" data-status="inactive" type="button">Admin Account (Inactive)</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="tab-btn inline-block p-4 border-b-2 rounded-t-lg text-gray-500 hover:text-gray-600 hover:border-gray-300"
                        data-role="user" data-status="active" type="button">User Account (Active)</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="tab-btn inline-block p-4 border-b-2 rounded-t-lg text-gray-500 hover:text-gray-600 hover:border-gray-300"
                        data-role="user" data-status="inactive" type="button">User Account (Inactive)</button>
                </li>
            </ul>
        </div>

        <!-- 🧾 Tables -->
        <div id="userTable" class="rounded-2xl bg-white rounded-lg border border-gray-200 dark:border-gray-700">
            @include('admin.users.table', ['role' => 'admin', 'status' => 'active'])
        </div>
    </div>
</main>
<script>
    const initialRole = "{{ session('role', 'admin') }}";
    const initialStatus = "{{ session('focus_tab', 'active') }}";

    const searchInput = document.getElementById('search');
    const filterByInput = document.getElementById('filterByElement');
    const tabButtons = document.querySelectorAll('.tab-btn');
    let activeRole = initialRole;
    let activeStatus = initialStatus;

    let timeout = null;

    // 🔄 Fetch users based on tab + search + status
    function fetchUsers() {
        const search = searchInput.value;
        const filterBy = filterByInput.value;
        fetch(`{{ route('admin.users') }}?search=${encodeURIComponent(search)}&filterBy=${encodeURIComponent(filterBy)}&role=${encodeURIComponent(activeRole)}&status=${encodeURIComponent(activeStatus)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('userTable').innerHTML = html;
        })
        .catch(err => console.error(err));
    }

    // 🔍 Live search
    searchInput.addEventListener('keyup', () => {
        clearTimeout(timeout);
        timeout = setTimeout(fetchUsers, 300);
    });

    


    // 🧭 Tab switch
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.classList.remove('text-blue-600', 'border-blue-600', 'active'));
            btn.classList.add('text-blue-600', 'border-blue-600', 'active');
            activeRole = btn.dataset.role;
            activeStatus = btn.dataset.status;
            fetchUsers();
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
    const targetTab = document.querySelector(
        `.tab-btn[data-role="${activeRole}"][data-status="${activeStatus}"]`
    );

    if (targetTab) {
        targetTab.click(); // ✅ now works
    } else {
        fetchUsers(); // fallback
    }
});


    // 📄 Handle pagination dynamically with query params preserved
    document.addEventListener('click', function(e) {
        if (e.target.matches('#userTable .pagination-link')) {
            e.preventDefault();
            
            // Get current search params
            const search = searchInput.value;
            const filterBy = filterByInput.value;
            const url = new URL(e.target.href);
            
            // Preserve search params
            if (search) url.searchParams.set('search', search);
            if (filterBy) url.searchParams.set('filterBy', filterBy);
            url.searchParams.set('role', activeRole);
            url.searchParams.set('status', activeStatus);
            
            // Add loading indicator
            const userTable = document.getElementById('userTable');
            userTable.style.opacity = '0.6';
            userTable.style.pointerEvents = 'none';
            
            // Fetch with preserved params
            fetch(url.toString(), { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' } 
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('userTable').innerHTML = html;
                userTable.style.opacity = '1';
                userTable.style.pointerEvents = 'auto';
                // Scroll to table
                document.getElementById('userTable').scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(err => {
                console.error('Pagination error:', err);
                userTable.style.opacity = '1';
                userTable.style.pointerEvents = 'auto';
            });
        }
    });
</script>
@endsection
