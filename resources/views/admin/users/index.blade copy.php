@extends('layouts.admin.app')

@section('title', 'User Managements')

@section('content')

<!-- Success Notification -->
@if (session('success'))
    <x-alert-message type="success">
        <ul class="list-disc list-inside space-y-1 text-sm">
            <li>{{ session('success') }}</li>
        </ul>
    </x-alert-message>
@endif
<main >
<div >
    <div class="w-full p-3 bg-background-light dark:bg-background-dark sticky top-0 z-10 border-b border-gray-200 dark:border-gray-800">
        
<div class="flex items-center gap-4">
<div class="flex-1">
<label class="flex flex-col w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-10">
    
    <input type="text" id="search" placeholder="Search users..."
                class="w-1/2 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300"
                autocomplete="off">
                <a href="{{ route('admin.users') }}"
            id="clearSearch" 
            class="ml-2 px-3 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300 transition"
        >
            Clear
</a>
</div>
</label>
</div>
<div class="relative">
    <select id="roleFilter"
        class="block w-48 h-10 px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:ring focus:ring-blue-300 focus:outline-none">
        <option value="">All Roles</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select>
    <script>
    const searchInput = document.getElementById('search');
    const roleFilter = document.getElementById('roleFilter');
    let timeout = null;

    function fetchUsers() {
        let search = searchInput.value;
        let role = roleFilter.value;

        fetch(`{{ route('admin.users') }}?search=${encodeURIComponent(search)}&role=${encodeURIComponent(role)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('userTable').innerHTML = html;
        })
        .catch(err => console.error(err));
    }

    // 🔍 Live search
    searchInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(fetchUsers, 300);
    });

    // 🎯 Role filter
    roleFilter.addEventListener('change', fetchUsers);

    // 📄 Handle pagination clicks dynamically
    document.addEventListener('click', function(e) {
        if (e.target.matches('#userTable .pagination a')) {
            e.preventDefault();
            fetch(e.target.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('userTable').innerHTML = html;
            });
        }
    });
</script>

</div>
<a href="{{ route('admin.users.create') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
<span class="material-symbols-outlined">add</span>
<span class="truncate">Create New User</span>
</a>
</div>

</div>
<div class="flex-1 p-3">
<div id="userTable" class="rounded-2xl bg-white  rounded-lg border border-gray-200 dark:border-gray-700">
        @include('admin.users.table')
</div>

</main>
<script>
    const searchInput = document.getElementById('search');
    let timeout = null;

    searchInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            let search = this.value;

            fetch(`{{ route('admin.users') }}?search=${encodeURIComponent(search)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('userTable').innerHTML = html;
            })
            .catch(err => console.error(err));
        }, 300);
    });

    // Handle pagination clicks dynamically
    document.addEventListener('click', function(e) {
        if (e.target.matches('#userTable .pagination a')) {
            e.preventDefault();
            fetch(e.target.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('userTable').innerHTML = html;
            });
        }
    });
</script>
@endsection
