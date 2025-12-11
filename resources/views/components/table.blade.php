@props([
    'columns' => [],
    'items' => [],
    'actions' => [],
    'badgeFields' => [], // fields that should show as badges, e.g. ['status']
    'searchParams' => [], // Query parameters to preserve in pagination
])

<div class="overflow-x-auto bg-white rounded-lg shadow scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
    <table class="min-w-[1200px] w-full table-auto border-collapse">
        <thead class="bg-gray-100 text-gray-700 uppercase text-sm sticky top-0 z-10">
            <tr>
                <th class="py-3.5 pl-4 pr-3 text-left w-12" scope="col">
                    <input type="checkbox" class="h-4 w-4 rounded border-gray-300 bg-transparent text-primary focus:ring-primary">
                </th>
                @foreach ($columns as $column)
                    <th class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-gray-600"
                        style="width: {{ $column['width'] ?? 'auto' }}">
                        {{ $column['label'] ?? ucfirst(str_replace('_', ' ', $column['field'])) }}
                    </th>
                @endforeach
                @if (!empty($actions))
                    <th class="px-3 py-3.5 text-center text-xs font-medium uppercase tracking-wider text-gray-600 whitespace-nowrap text-center sticky right-0 bg-white" style="width: 70px;">Actions</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse ($items as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 pl-4 pr-3 w-12 text-sm font-medium">
                        <input type="checkbox"
                               class="h-4 w-4 rounded border-gray-300 bg-transparent text-primary focus:ring-primary">
                    </td>

                    @foreach ($columns as $column)
                        @php
                            $value = data_get($item, $column['field']);
                        @endphp

                        <td class="border px-4 py-2 text-sm text-gray-700"
                        style="width: {{ $column['width'] ?? 'auto' }}"
                        >
                            {{-- If this column is a badge field --}}
                            @if(in_array($column['field'], $badgeFields))
                                @if(strtolower(trim($value)) === 'active')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
                                @elseif(strtolower(trim($value)) === 'inactive')
                                    <span class="inline-flex items-center rounded-full bg-gray-200 px-2.5 py-0.5 text-xs font-medium text-gray-700">Inactive</span>
                                @elseif(strtolower(trim($value)) === 'pending')
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pending</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">{{ ucfirst($value) }}</span>
                                @endif
                            @else
                                @if($column['field']==='question_stem' && !empty(data_get($item, 'image')))

                                    <img src="{{ asset('storage/question-images/'.data_get($item, 'image')) }}" class="w-16 h-auto rounded-md">
                                @endif
                                    {{ Str::limit($value, $column['limit'] ?? 80) }}
                                
                            @endif
                        </td>
                    @endforeach

                    {{-- Action buttons --}}
                    @if (!empty($actions))
                        <td class="border px-6 py-4 whitespace-nowrap text-right sticky right-0 bg-white" >
                        @if(isset($actions['status']))
                            
                            <a href="{{ route($actions['status'], $item->id) }}"
                            class="inline-flex items-center justify-center w-8 h-8 
                                    {{ $item->data_status === 'active' ? 'text-red-600 hover:bg-red-100' : 'text-green-500 hover:bg-green-100' }}
                                    rounded-full transition"
                            title="{{ $item->data_status === 'active' ? 'Deactivate User' : 'Activate User' }}">
                            
                            @if($item->data_status === 'active')
                                <x-heroicon-o-x-mark class="w-4 h-4" />   {{-- Active → show open eye --}}
                            @else
                                <x-heroicon-o-arrow-path-rounded-square class="w-4 h-4" /> {{-- Inactive → show eye with slash --}}
                            @endif
                            </a>
                        @endif   
                        @if(isset($actions['show']))
                                <a href="{{ route($actions['show'], $item->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-100 rounded-full"
                                   title="View">
                                   @if($actions['show']=='admin.users.show')
                                        <x-heroicon-o-folder-plus class="w-4 h-4" />
                                    @else
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    @endif
                                </a>
                            @endif

                            @if(isset($actions['edit']))
                                <a href="{{ route($actions['edit'], $item->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-100 rounded-full"
                                   title="Edit">
                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                </a>
                            @endif

                            @if(isset($actions['delete']) && $item->data_status != 'active')
                                <form 
                                    id="data-status-form-{{ $item->id }}" 
                                    action="{{ route($actions['delete'], ['id'=>$item->id]) }}" 
                                    method="POST" 
                                    style="display: inline;" 
                                    data-action="DELETE this data immediately"
                                >
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="data_status" value="inactive">
                                    <button type="button" 
                                            onclick="triggerConfirmModal('data-status-form-{{ $item->id }}')" {{-- <--- ADDED JS CALL --}}
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-100 rounded-full"
                                            title="Delete">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>
                                </form>
                            @endif
                            @if(isset($actions['remove']))
                                {{-- Check if item is not assigned to any exam --}}
                                @if(isset($item->exams) && $item->exams->count() == 0)
                                    <form 
                                        id="delete-form-{{ $item->id }}" 
                                        action="{{ route($actions['remove'], $item->id) }}" 
                                        method="POST" 
                                        style="display: inline;"
                                        data-action="DELETE this data immediately"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="triggerConfirmModal('delete-form-{{ $item->id }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-100 rounded-full"
                                                title="Delete">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                @else
                                    {{-- Show disabled button with tooltip if assigned --}}
                                    <button type="button" 
                                            disabled
                                            class="inline-flex items-center justify-center w-8 h-8 text-gray-400 bg-gray-100 rounded-full cursor-not-allowed"
                                            title="Cannot delete: Question is assigned to exams">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>
                                @endif
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + 2 }}" class="text-center py-4 text-gray-500">
                        No data available.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Improved Pagination -->
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 px-4 py-4 bg-gray-50 rounded-lg">
    <!-- Pagination Info -->
    <div class="text-sm text-gray-600">
        @if($items->total() > 0)
            Showing <span class="font-medium">{{ $items->firstItem() }}</span> to 
            <span class="font-medium">{{ $items->lastItem() }}</span> of 
            <span class="font-medium">{{ $items->total() }}</span> results
        @else
            <span class="text-gray-500">No results found</span>
        @endif
    </div>

    <!-- Pagination Links -->
    @if($items->hasPages())
        <div class="flex items-center gap-1 flex-wrap">
            {{-- Previous Page Link --}}
            @if ($items->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    ← Previous
                </span>
            @else
                <a href="{{ $items->previousPageUrl() }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    ← Previous
                </a>
            @endif

            {{-- First Page Link --}}
            @if($items->currentPage() > 3)
                <a href="{{ $items->url(1) }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    1
                </a>
                @if($items->currentPage() > 4)
                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                @endif
            @endif

            {{-- Pagination Elements (Smart Range) --}}
            @php
                $start = max(1, $items->currentPage() - 2);
                $end = min($items->lastPage(), $items->currentPage() + 2);
            @endphp

            @foreach ($items->getUrlRange($start, $end) as $page => $url)
                @if ($page == $items->currentPage())
                    <span class="pagination-link px-3 py-2 text-sm text-white bg-primary rounded-lg font-medium">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Last Page Link --}}
            @if($items->currentPage() < $items->lastPage() - 2)
                @if($items->currentPage() < $items->lastPage() - 3)
                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                @endif
                <a href="{{ $items->url($items->lastPage()) }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    {{ $items->lastPage() }}
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    Next →
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    Next →
                </span>
            @endif
        </div>
    @endif
</div>
