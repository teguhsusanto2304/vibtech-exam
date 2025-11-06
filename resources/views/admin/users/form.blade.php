@extends('layouts.admin.app')

@section('title', 'User Management')

@section('content')
@if ($errors->any())
    <x-alert-message type="error">
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
<div class="w-full bg-white shadow-xl rounded-2xl flex-1 p-4 ml-2 mr-8">

    <form 
        method="POST" 
        action="{{ isset($user) ? route('admin.users.update', ['id'=>$user->id]) : route('admin.users.store') }}" 
        class="space-y-6"
    >
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <!-- Full-width grid form -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div >
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $user->name ?? '') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2"
                       placeholder="Enter full name" required>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email ?? '') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2"
                       placeholder="Enter email address" required>
            </div>

            <!-- Company -->
            <div>
                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                <input type="text" id="company" name="company"
                       value="{{ old('company', $user->company ?? '') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2"
                       placeholder="Enter company name">
            </div>

            <!-- Status -->
            <div>
                <label for="data_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="data_status" name="data_status"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                    @foreach(['active', 'inactive', 'suspended'] as $status)
                        <option value="{{ $status }}" {{ old('data_status', $user->data_status ?? '') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="role" name="role"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                    <option value="">Choose Role</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>

            
            <!-- Password (full width) -->
            <div class="md:col-span-1">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <div class="flex">
                    <input 
                        type="text" 
                        id="password" 
                        name="password"
                        class="flex-1 border border-gray-300 rounded-l-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2"
                        placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Enter password' }}"
                    >
                    <button 
                        type="button" 
                        onclick="generatePassword()"
                        class="px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg transition-colors"
                    >
                        Generate
                    </button>
                </div>
            </div>

<script>
function generatePassword() {
    const length = 12;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
    let password = "";
    for (let i = 0, n = charset.length; i < length; ++i) {
        password += charset.charAt(Math.floor(Math.random() * n));
    }

    const input = document.getElementById("password");
    input.value = password;

    // Optional: temporarily show password
    input.type = "text";
    setTimeout(() => input.type = "text", 2000);
}
</script>
        </div>

        <!-- Action Buttons -->
        <div class="pt-6 flex justify-end gap-3 border-t mt-6">
            <a href="{{ route('admin.users') }}" 
               class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition">
                {{ isset($user) ? 'Update User' : 'Create User' }}
            </button>
        </div>
    </form>
</div>
@endsection
