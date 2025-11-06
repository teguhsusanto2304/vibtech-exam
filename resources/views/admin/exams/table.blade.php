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
                        <p class="text-sm">{{ $exam->description }}</p>
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

                        <a href="{{ route('admin.exams.questions',['id'=>$exam->id]) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-purple-600 hover:bg-purple-100 rounded-full"
                                   title="Question Manage">
                                    <x-heroicon-o-folder-plus class="w-4 h-4" />
                                </a>
                        <a href="{{ route('admin.exams.show',['id'=>$exam->id]) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-100 rounded-full"
                                   title="View">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.exams.edit',['id'=>$exam->id]) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-100 rounded-full"
                                   title="Edit">
                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                </a>
        
                                <form action="{{ route('admin.exams.destroy',['id'=>$exam->id]) }}"
      method="POST" 
      class="inline needs-confirmation" 
      data-action="delete the exam '{{ $exam->title ?? $exam->id }}'"> @csrf
    @method('DELETE')
    
    <button type="submit"  class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-100 rounded-full"
            title="Delete">
        <x-heroicon-o-trash class="w-4 h-4" />
    </button>
</form>
                                
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
<div class="flex items-center justify-end mt-4 mb-4 mr-4 px-4">    
    <div>
        {{ $exams->onEachSide(1)->links('vendor.pagination.tailwind') }}
    </div>
</div>
