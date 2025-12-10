# Panduan Perbaikan Pagination di Laravel

## 🎯 Masalah Umum Pagination

### 1. **Pagination hilang saat search/filter**
   - Query parameter tidak di-preserve
   - State tidak di-maintain

### 2. **Pagination tidak responsive**
   - Layout rusak di mobile
   - Links terlalu panjang

### 3. **Pagination tidak integrate dengan AJAX**
   - Page reload penuh
   - UX jelek

### 4. **Styling tidak konsisten**
   - Custom Tailwind tidak match
   - Buttons tidak aligned

---

## ✅ Solusi Lengkap

### Step 1: Update Component Table.php

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Pagination\Paginator;

class Table extends Component
{
    public $items;
    public $columns;
    public $actions;
    public $badgeFields;
    public $searchParams; // NEW: untuk preserve query params

    /**
     * Create a new component instance.
     */
    public function __construct($items, $columns, $actions = [], $badgeFields = [], $searchParams = [])
    {
        $this->items = $items;
        $this->columns = $columns;
        $this->actions = $actions;
        $this->badgeFields = $badgeFields ?? [];
        $this->searchParams = $searchParams; // NEW
    }

    /**
     * Get the view / contents.
     */
    public function render()
    {
        return view('components.table');
    }
}
```

### Step 2: Update Table.blade.php

Replace pagination section dengan yang lebih baik:

```blade
<!-- Pagination Container -->
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
        <div class="flex items-center gap-1">
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

            {{-- Pagination Elements --}}
            @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
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
```

### Step 3: Update index.blade.php untuk handle pagination

```javascript
// Replace the pagination event handler dengan yang lebih robust:

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
        
        // Fetch with preserved params
        fetch(url.toString(), { 
            headers: { 'X-Requested-With': 'XMLHttpRequest' } 
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('userTable').innerHTML = html;
            // Scroll to table
            document.getElementById('userTable').scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(err => console.error('Pagination error:', err));
    }
});
```

### Step 4: Update Controller untuk include search params

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        $role = $request->query('role', 'admin');
        $status = $request->query('status', 'active');
        $search = $request->query('search', '');
        $filterBy = $request->query('filterBy', 'name');

        $query = User::query()
            ->where('role', $role)
            ->where('data_status', $status);

        // Apply search filter
        if ($search) {
            $query->where($filterBy, 'LIKE', "%{$search}%");
        }

        // Paginate dengan 10 items per page
        $users = $query->paginate(10);

        // Preserve query params di pagination links
        $users->appends([
            'search' => $search,
            'filterBy' => $filterBy,
            'role' => $role,
            'status' => $status,
        ]);

        // Return partial jika AJAX
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.users.table', ['users' => $users]);
        }

        return view('admin.users.index', ['users' => $users]);
    }
}
```

---

## 🎨 Custom Pagination View (Optional tapi Recommended)

### Create file: `resources/views/vendor/pagination/custom.blade.php`

```blade
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <!-- Mobile Responsive -->
        <div class="flex-1 flex justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 active:bg-light-gray active:text-gray-700 transition ease-in-out duration-150">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 active:bg-light-gray active:text-gray-700 transition ease-in-out duration-150">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <!-- Desktop Responsive -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    {!! __('Showing') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md gap-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-l-md" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 rounded-l-md hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 active:bg-light-gray active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-primary border border-primary-500 cursor-default leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="pagination-link relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 active:bg-light-gray active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5 rounded-r-md hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 active:bg-light-gray active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-r-md" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
```

---

## 🚀 Testing

### 1. Test Search with Pagination

```bash
# Test dengan search parameter
GET /admin/users?search=john&filterBy=name&page=2

# Expected: 
# - Search preserved saat page berubah
# - Tidak ada double query di URL
```

### 2. Test Filter with Pagination

```bash
# Test dengan multiple filters
GET /admin/users?role=user&status=active&page=2

# Expected:
# - Pagination links include semua query params
# - Clicking page links maintain filter
```

### 3. Test AJAX Pagination

```javascript
// Di browser console
document.querySelector('#userTable .pagination-link').click();

// Expected:
// - No full page reload
// - Only table updates
// - Smooth scroll to table
```

---

## 📊 Per Page Items Configuration

### Di Controller:
```php
// Default 10 items per page
$users = $query->paginate(10);

// Custom per page
$users = $query->paginate(request()->input('per_page', 10));

// Allow user to select
$users = $query->paginate(request()->input('per_page', 15) >= 100 ? 100 : request()->input('per_page', 15));
```

### Di Blade View:
```blade
<select id="perPageSelect" class="px-3 py-2 border rounded-lg">
    <option value="10" @selected(request('per_page') == 10)>10 per page</option>
    <option value="25" @selected(request('per_page') == 25)>25 per page</option>
    <option value="50" @selected(request('per_page') == 50)>50 per page</option>
    <option value="100" @selected(request('per_page') == 100)>100 per page</option>
</select>

<script>
document.getElementById('perPageSelect').addEventListener('change', function(e) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', e.target.value);
    url.searchParams.set('page', '1'); // Reset to first page
    window.location = url.toString();
});
</script>
```

---

## 🎯 Best Practices

### ✅ DO:
- Preserve query parameters saat pagination
- Use `appends()` untuk maintain query string
- Scroll to top when pagination changed
- Show loading indicator during AJAX
- Cache pagination data jika data static

### ❌ DON'T:
- Reset page number setiap kali search berubah
- Hardcode pagination links
- Fetch tanpa preserving filter params
- Over-paginate (terlalu banyak per page)
- Ignore mobile responsiveness

---

## 🔧 Troubleshooting

| Masalah | Solusi |
|--------|--------|
| Pagination hilang setelah search | Add `appends()` di controller |
| Page reset saat filter | Check URL params di fetch request |
| AJAX tidak bekerja | Verify `X-Requested-With` header |
| Links tidak clickable | Check event delegation di JS |
| Mobile layout rusak | Use flex dan responsive padding |

---

**Last Updated:** December 8, 2025

