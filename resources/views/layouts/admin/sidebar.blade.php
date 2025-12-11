<aside class="sticky top-0 h-screen w-64 flex-shrink-0 flex-col border-r border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark hidden lg:flex">
<div class="flex items-center gap-4 h-[65px] border-b border-solid border-border-light dark:border-border-dark px-6">
@php
    $appLogo = getSetting('app_logo', '/images/logo.png');
    $appName = getSetting('app_name', 'Vibtech Genesis');
@endphp
@if ($appLogo && $appLogo !== '/images/logo.png')
<img src="{{ $appLogo }}" alt="Logo" class="size-6 object-contain">
@else
<div class="text-primary size-6">
<svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
@endif
<h2 class="text-gray-800 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">{{ $appName }}</h2>
</div>
<nav class="flex-1 p-4">
<ul class="flex flex-col gap-2">
<li style="display:none;">
<a class="flex items-center gap-3 rounded-lg px-3 py-2 {{ Request::routeIs('admin.dashboard') 
              ? 'bg-primary/10 text-primary dark:text-primary' 
              : 'text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary' }} dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.dashboard') }}">
<span class="material-symbols-outlined text-xl">monitoring</span>
<span class="text-sm font-semibold">Dashboard</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 {{ Request::routeIs('admin.users') || Request::routeIs('admin.users.create') || Request::routeIs('admin.users.show')
              ? 'bg-primary/10 text-primary dark:text-primary' 
              : 'text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary' }} dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.users') }}">
<span class="material-symbols-outlined text-xl">group</span>
<span class="text-sm font-semibold">Account Management</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 {{ Request::routeIs('admin.exams') || Request::routeIs('admin.exams.create')
              ? 'bg-primary/10 text-primary dark:text-primary' 
              : 'text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary' }} dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.exams') }}">
<span class="material-symbols-outlined text-xl">settings</span>
<span class="text-sm font-semibold">Exam Management</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 {{ Request::routeIs('admin.question-banks') || Request::routeIs('admin.question-banks.create')
              ? 'bg-primary/10 text-primary dark:text-primary' 
              : 'text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary' }} dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.question-banks') }}">
<span class="material-symbols-outlined text-xl">quiz</span>
<span class="text-sm font-semibold">Question Management</span>
</a>
</li>
<li>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 {{ Request::routeIs('admin.settings')
              ? 'bg-primary/10 text-primary dark:text-primary' 
              : 'text-text-light-secondary dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary' }} dark:text-text-dark-secondary hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('admin.settings') }}">
<span class="material-symbols-outlined text-xl">tune</span>
<span class="text-sm font-semibold">Settings</span>
</a>
</li>
<li>
<a href="#" 
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
   class="flex items-center gap-3 rounded-lg px-3 py-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
    <span class="material-symbols-outlined text-xl">logout</span>
    <span class="text-sm font-semibold">Logout</span>
</a>

{{-- Hidden Form for secure POST request --}}
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
    @csrf
</form>
</li>
</ul>
</nav>
</aside>