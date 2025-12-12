<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    EXAM TITLE
                </th>
                
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    QUESTIONS
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    DURATION
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    STATUS
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    LAST MODIFIED
                </th>
                <th class="px-3 py-3.5 text-center text-xs font-medium uppercase tracking-wider text-gray-600 whitespace-nowrap text-center sticky right-0 bg-white" style="width: 70px;">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($exams as $exam)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <h3 class="font-medium">{{ $exam->title }}</h3>
                        <p class="text-sm">{!! $exam->description !!}</p>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $exam->examQuestions->count() }} of {{ $exam->questions }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $exam->duration }} mins
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = '';
                            switch ($exam->data_status) {
                                case 'published':
                                    $statusClass = 'bg-green-100 text-green-800';
                                    break;
                                case 'draft':
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'archived':
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    break;
                                default:
                                    $statusClass = 'bg-blue-100 text-blue-800'; // Default for other statuses
                                    break;
                            }
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($exam->data_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $exam->last_modified ? $exam->last_modified->format('d-m-Y') : 'N/A' }}
                    </td>
                    <!-- px-6 py-4 whitespace-nowrap text-right sticky right-0 bg-white z-10 -->
                    <td class="px-6 py-4 whitespace-nowrap text-right sticky right-0 bg-white">
                        @if($exam->data_status==='draft')
                        <a href="{{ route('admin.exams.questions',['id'=>$exam->id]) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-purple-600 hover:bg-purple-100 rounded-full"
                                   title="Question Manage">
                                    <x-heroicon-o-folder-plus class="w-4 h-4" />
                                </a>
                        @endif
                        <a href="{{ route('admin.exams.show',['id'=>$exam->id]) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-100 rounded-full"
                                   title="View">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </a>
                                @if($exam->data_status==='publish')
                                <form 
                                    id="exam-status-form-{{ $exam->id }}" 
                                    action="{{ route('admin.exams.change-status', ['id'=>$exam->id]) }}" 
                                    method="POST" 
                                    style="display: inline;" 
                                    data-action="DRAFT this exam immediately"
                                >
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="data_status" value="draft">
                                    <button type="button" 
                                            onclick="triggerConfirmModal('exam-status-form-{{ $exam->id }}')" {{-- <--- ADDED JS CALL --}}
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-100 rounded-full"
                                            title="Draft">
                                        <x-heroicon-o-lock-open class="w-4 h-4" />
                                    </button>
                                </form>
                                @endif
                                @if($exam->data_status==='draft')
                                @if($exam->data_status==='draft11')
                                <form 
                                    id="exam-status-form-{{ $exam->id }}" 
                                    action="{{ route('admin.exams.update-status', ['id'=>$exam->id]) }}" 
                                    method="POST" 
                                    style="display: inline;" 
                                    data-action="PUBLISH this exam immediately"
                                >
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="data_status" value="publish">
                                    <button type="button" 
                                            onclick="triggerConfirmModal('exam-status-form-{{ $exam->id }}')" {{-- <--- ADDED JS CALL --}}
                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-100 rounded-full"
                                            title="Publish">
                                        <x-heroicon-o-lock-closed class="w-4 h-4" />
                                    </button>
                                </form>
                                @endif

                                <a href="{{ route('admin.exams.edit',['id'=>$exam->id]) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-100 rounded-full"
                                   title="Edit">
                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                </a>
                                @endif
                                @if($exam->data_status==='draft')
                                <form 
                                    id="exam-status-form-{{ $exam->id }}" 
                                    action="{{ route('admin.exams.change-status', ['id'=>$exam->id]) }}" 
                                    method="POST" 
                                    style="display: inline;" 
                                    data-action="DELETE this exam immediately"
                                >
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="data_status" value="archived">
                                    <button type="button" 
                                            onclick="triggerConfirmModal('exam-status-form-{{ $exam->id }}')" {{-- <--- ADDED JS CALL --}}
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-100 rounded-full"
                                            title="Delete">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>
                                </form>
                                @endif
                                @if($exam->data_status==='archived')
                                <form 
                                    id="exam-status-form-{{ $exam->id }}" 
                                    action="{{ route('admin.exams.update-status', ['id'=>$exam->id]) }}" 
                                    method="POST" 
                                    style="display: inline;" 
                                    data-action="DRAFT this exam immediately"
                                >
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="data_status" value="draft">
                                    <button type="button" 
                                            onclick="triggerConfirmModal('exam-status-form-{{ $exam->id }}')" {{-- <--- ADDED JS CALL --}}
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-100 rounded-full"
                                            title="Restore">
                                        <x-heroicon-o-arrow-path-rounded-square class="w-4 h-4" />
                                    </button>
                                </form>
                                @endif
                                
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        No exams found.
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
        @if($exams->total() > 0)
            Showing <span class="font-medium">{{ $exams->firstItem() }}</span> to 
            <span class="font-medium">{{ $exams->lastItem() }}</span> of 
            <span class="font-medium">{{ $exams->total() }}</span> results
        @else
            <span class="text-gray-500">No results found</span>
        @endif
    </div>

    <!-- Pagination Links -->
    @if($exams->hasPages())
        <div class="flex items-center gap-1 flex-wrap">
            {{-- Previous Page Link --}}
            @if ($exams->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    ← Previous
                </span>
            @else
                <a href="{{ $exams->previousPageUrl() }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    ← Previous
                </a>
            @endif

            {{-- First Page Link --}}
            @if($exams->currentPage() > 3)
                <a href="{{ $exams->url(1) }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    1
                </a>
                @if($exams->currentPage() > 4)
                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                @endif
            @endif

            {{-- Pagination Elements (Smart Range) --}}
            @php
                $start = max(1, $exams->currentPage() - 2);
                $end = min($exams->lastPage(), $exams->currentPage() + 2);
            @endphp

            @foreach ($exams->getUrlRange($start, $end) as $page => $url)
                @if ($page == $exams->currentPage())
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
            @if($exams->currentPage() < $exams->lastPage() - 2)
                @if($exams->currentPage() < $exams->lastPage() - 3)
                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                @endif
                <a href="{{ $exams->url($exams->lastPage()) }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    {{ $exams->lastPage() }}
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($exams->hasMorePages())
                <a href="{{ $exams->nextPageUrl() }}" class="pagination-link px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
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