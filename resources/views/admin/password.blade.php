@extends('layouts.admin.app')

@section('title', $pageTitle)

@section('content')
<main class="flex flex-1 justify-center py-8 px-4 sm:px-6 lg:px-8">
<div class="w-full max-w-2xl">
    @if (session('success'))
        <x-alert-message type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    @if ($errors->any())
        <x-alert-message type="error">
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
    @endif
<div class="bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/50 rounded-xl shadow-sm">

<form method="POST" action="{{ route('admin.update-password') }}" class="p-6 sm:p-8 space-y-6">
    @csrf
{{-- Current Password --}}
<div class="flex flex-col">
    <label class="text-sm font-medium pb-2">Current Password</label>
    <div class="relative flex w-full items-stretch">
        <input 
            name="current_password"
            id="current_password"
            type="password"
            required
            class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600"
            placeholder="Enter your current password"
        />
    </div>
</div>

{{-- New Password --}}
<div class="flex flex-col">
    <label class="text-sm font-medium pb-2">New Password</label>
    <div class="relative flex w-full items-stretch">
        <input 
            name="new_password"
            id="new-password"
            type="password"
            required
            class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600"
            placeholder="Enter new password"
        />
    </div>
</div>

{{-- Confirm Password --}}
<div class="flex flex-col">
    <label class="text-sm font-medium pb-2">Confirm New Password</label>
    <div class="relative flex w-full items-stretch">
        <input 
            name="new_password_confirmation"
            id="confirm-password"
            type="password"
            required
            class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600"
            placeholder="Confirm new password"
        />
    </div>
</div>

{{-- Buttons INSIDE the form --}}
<div class="flex justify-end gap-4 border-t px-6 py-4 bg-gray-50 rounded-b-xl">
    <a href="{{ route('admin.profile') }}" class="rounded-lg px-6 py-3 bg-gray-200 text-sm font-bold">
        Cancel
    </a>
    <button type="submit" class="rounded-lg px-6 py-3 bg-primary text-white font-bold hover:bg-primary/90">
        Update Password
    </button>
</div>

</form>

</div>
</div>
</main>
<script>
    document.querySelectorAll('.toggle-pass').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = btn.previousElementSibling;
            const icon = btn.querySelector('span');

            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "visibility";
            } else {
                input.type = "password";
                icon.textContent = "visibility_off";
            }
        });
    });
</script>
@endsection
