@props([
    'type' => 'success', // success, error, warning, info
    'message' => null,
])

@php
    $colors = [
        'success' => [
            'bg' => 'bg-green-100',
            'border' => 'border-green-500',
            'text' => 'text-green-800',
            'icon' => 'text-green-500',
            'hover' => 'hover:bg-green-200',
        ],
        'error' => [
            'bg' => 'bg-red-100',
            'border' => 'border-red-500',
            'text' => 'text-red-800',
            'icon' => 'text-red-500',
            'hover' => 'hover:bg-red-200',
        ],
        'warning' => [
            'bg' => 'bg-yellow-100',
            'border' => 'border-yellow-500',
            'text' => 'text-yellow-800',
            'icon' => 'text-yellow-500',
            'hover' => 'hover:bg-yellow-200',
        ],
        'info' => [
            'bg' => 'bg-blue-100',
            'border' => 'border-blue-500',
            'text' => 'text-blue-800',
            'icon' => 'text-blue-500',
            'hover' => 'hover:bg-blue-200',
        ],
    ];

    $color = $colors[$type];
@endphp

<div x-data="{ show: true }" x-show="show"
     class="p-4 mb-4 {{ $color['bg'] }} border-l-4 {{ $color['border'] }} rounded-md shadow-sm"
     role="alert">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            @if ($type === 'success')
                <svg class="w-6 h-6 {{ $color['icon'] }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
                </svg>
            @elseif ($type === 'error')
                <svg class="w-6 h-6 {{ $color['icon'] }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0a9 9 0 0118 0z"/>
                </svg>
            @elseif ($type === 'warning')
                <svg class="w-6 h-6 {{ $color['icon'] }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856A2.062 2.062 0 0020 16.938L12 4l-8 12.938A2.062 2.062 0 005.062 19z"/>
                </svg>
            @else
                <svg class="w-6 h-6 {{ $color['icon'] }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20h.01M12 4h.01"/>
                </svg>
            @endif

            <p class="text-sm font-semibold {{ $color['text'] }}">
                {{ $message ?? $slot }}
            </p>
        </div>

        <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 {{ $color['bg'] }} {{ $color['icon'] }} rounded-lg p-1.5 {{ $color['hover'] }}"
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
