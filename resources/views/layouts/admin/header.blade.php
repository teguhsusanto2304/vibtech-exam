@php
    $overdueExams = getOverDueUserExams();
@endphp
<header class="p-6 bg-background-light dark:bg-background-dark sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800">
    <div class="flex justify-between items-center">
        <h1 class="text-gray-800 dark:text-white text-3xl font-black">{{ $pageTitle ?? 'Dashboard' }}</h1>

        <div class="flex items-center gap-4 relative">

            {{-- 🔔 Notification Button --}}
            <button id="notificationToggle" class="relative px-3 py-2 text-gray-600 hover:text-black">
                <span class="material-symbols-outlined text-gray-600">notifications</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-1">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            {{-- 👤 User Avatar --}}
            <a href="{{ route('admin.profile') }}">
            <div class="bg-center bg-cover rounded-full size-10 shadow-sm"
                 style='background-image: url("https://lh3.googleusercontent.com/a/default-user")'>
            </div>
            </a>

            {{-- 🔽 Notification Dropdown --}}
            <div id="notificationDropdown"
                 class="hidden absolute right-0 top-12 w-80 max-h-80 overflow-y-auto bg-white dark:bg-gray-900 shadow-xl border dark:border-gray-700 rounded-lg p-4 z-50">

                <h4 class="font-semibold text-sm mb-3 text-gray-800 dark:text-gray-200">Notifications</h4>

                @forelse(auth()->user()->unreadNotifications as $notification)
                    <div class="border-b last:border-none pb-2 mb-3 relative group">
                        <div class="font-semibold text-gray-900 dark:text-gray-100 pr-6">
                            {{ $notification->data['title'] ?? 'Exam Update' }}
                        </div>

                        <div class="text-gray-600 dark:text-gray-400 text-sm">
                            {!! $notification->data['message'] ?? '' !!}
                        </div>

                        {{-- X Button to delete notification --}}
                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" class="delete-notification-form absolute top-0 right-0">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    onclick="deleteNotification(event, '{{ $notification->id }}')"
                                    class="text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900 rounded-full p-1 transition"
                                    title="Delete notification">
                                <x-heroicon-o-x-mark class="w-4 h-4" />
                            </button>
                        </form>

                        @if(!$notification->read_at)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="mt-1">
                            @csrf
                            <button class="text-blue-600 text-xs hover:underline">
                                Read more
                            </button>
                        </form>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No notifications available.</p>
                @endforelse
            </div>
        </div>
    </div>
</header>
<script>
    const toggleBtn = document.getElementById('notificationToggle');
    const dropdown = document.getElementById('notificationDropdown');

    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });

    // Close when clicking anywhere else
    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) dropdown.classList.add('hidden');
    });

    // Delete notification via AJAX
    function deleteNotification(event, notificationId) {
        event.preventDefault();
        
        const form = event.target.closest('.delete-notification-form');
        
        // Get CSRF token dari meta tag atau dari form
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        // Fallback: ambil dari form input
        if (!csrfToken) {
            const tokenInput = document.querySelector('input[name="_token"]');
            csrfToken = tokenInput ? tokenInput.value : '';
        }
        
        // Jika masih tidak ada, gunakan form submission biasa
        if (!csrfToken) {
            form.submit();
            return;
        }
        
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok || response.status === 200 || response.status === 204) {
                // Remove the notification item from DOM
                const notificationItem = form.closest('div[class*="border-b"]');
                if (notificationItem) {
                    notificationItem.remove();
                }
                
                // Update notification count badge
                const badge = document.querySelector('#notificationToggle span');
                if (badge) {
                    const count = parseInt(badge.textContent) - 1;
                    if (count > 0) {
                        badge.textContent = count;
                    } else {
                        badge.remove();
                    }
                }
                
                // If no notifications left, show empty message
                const notificationItems = document.querySelectorAll('#notificationDropdown > div[class*="border-b"]').length;
                if (notificationItems === 0) {
                    const dropdown = document.getElementById('notificationDropdown');
                    dropdown.innerHTML = '<h4 class="font-semibold text-sm mb-3 text-gray-800 dark:text-gray-200">Notifications</h4><p class="text-gray-500 dark:text-gray-400 text-sm">No notifications available.</p>';
                }
            } else {
                console.error('Error response:', response);
                alert('Failed to delete notification');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting notification');
        });
    }
</script>