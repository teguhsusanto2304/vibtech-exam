# Contoh Implementasi Controller dengan Pagination yang Diperbaiki

## File: app/Http/Controllers/AdminUserController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users with improved pagination
     * 
     * Features:
     * - Query parameters preserved in pagination
     * - Search filtering dengan multiple fields
     * - AJAX support untuk seamless UX
     * - Configurable per_page items
     * - Security: sanitized inputs & SQL injection prevention
     */
    public function index(Request $request)
    {
        // Get filter parameters dengan default values
        $role = $request->query('role', 'admin');
        $status = $request->query('status', 'active');
        $search = $request->query('search', '');
        $filterBy = $request->query('filterBy', 'name');
        $perPage = $request->query('per_page', 10);

        // Validate per_page to prevent abuse
        $perPage = min((int)$perPage, 100);
        $perPage = max($perPage, 5);

        // Validate filterBy untuk prevent invalid columns
        $validFilterFields = ['name', 'email', 'company'];
        if (!in_array($filterBy, $validFilterFields)) {
            $filterBy = 'name';
        }

        // Build query dengan role & status filter
        $query = User::query()
            ->where('role', $role)
            ->where('data_status', $status);

        // Apply search filter jika ada
        if ($search && !empty(trim($search))) {
            $search = trim($search);
            $query->where($filterBy, 'LIKE', "%{$search}%");
        }

        // Order by latest dan paginate
        $users = $query
            ->latest('id')
            ->paginate($perPage);

        // ⭐ PENTING: Preserve semua query parameters di pagination links
        $users->appends([
            'search' => $search,
            'filterBy' => $filterBy,
            'role' => $role,
            'status' => $status,
            'per_page' => $perPage,
        ]);

        // Return different responses based on request type
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Return table partial untuk AJAX requests
            return view('admin.users.table', ['users' => $users]);
        }

        // Return full page untuk normal requests
        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show user edit form
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'company' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->data_status = $user->data_status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->back()
            ->with('success', "User status changed to {$user->data_status}.");
    }

    /**
     * Delete user
     */
    public function destroy($id, Request $request)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return redirect()->back()
                ->with('error', 'Cannot delete admin users.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }
}
```

## Routes Configuration

Update di `routes/web.php`:

```php
Route::middleware(['auth'])->group(function () {
    // Admin Users Management
    Route::prefix('admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
        Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::post('/users/{id}/status', [AdminUserController::class, 'toggleStatus'])->name('admin.users.status');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });
});
```

---

## 🔑 Key Points Perbaikan

### 1. **Query Parameters Preservation**
```php
// ⭐ MOST IMPORTANT: ini yang sering lupa!
$users->appends([
    'search' => $search,
    'filterBy' => $filterBy,
    'role' => $role,
    'status' => $status,
    'per_page' => $perPage,
]);
```

**Why penting?**
- Saat user klik pagination link, semua filter tetap terjaga
- Tidak perlu click filter lagi setiap ganti page

### 2. **AJAX Support**
```php
// Check if request adalah AJAX
if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
    return view('admin.users.table', ['users' => $users]);
}
```

**Why penting?**
- Seamless page transition tanpa reload
- Smooth user experience
- Preserve search/filter state

### 3. **Security**
```php
// Validate per_page
$perPage = min((int)$perPage, 100);
$perPage = max($perPage, 5);

// Validate filterBy column
$validFilterFields = ['name', 'email', 'company'];
if (!in_array($filterBy, $validFilterFields)) {
    $filterBy = 'name';
}
```

**Why penting?**
- Prevent abuse (per_page 9999999)
- SQL injection prevention
- Limit database load

---

## 📊 Comparison: Before vs After

### ❌ BEFORE (Problem)
```php
// Tanpa appends() - query params hilang!
$users = User::where('role', $role)->paginate(10);

// Result: Pagination links jadi:
// /admin/users?page=2
// Search params & filter hilang!
```

### ✅ AFTER (Fixed)
```php
// Dengan appends() - semua params terjaga!
$users = User::where('role', $role)->paginate(10);
$users->appends(['search' => $search, 'role' => $role]);

// Result: Pagination links jadi:
// /admin/users?page=2&search=john&role=user
// Semua filter tetap!
```

---

## 🧪 Testing

### Test 1: Search + Pagination
```bash
# Go to search page 1
GET /admin/users?search=john&role=user&page=1

# Click page 2
# Expected: ?search=john&role=user&page=2
# ✅ Search parameter preserved!
```

### Test 2: Filter + Pagination
```bash
# Filter by admin active
GET /admin/users?role=admin&status=active&page=1

# Click page 2
# Expected: ?role=admin&status=active&page=2
# ✅ Filter parameters preserved!
```

### Test 3: AJAX Request
```javascript
// Open browser DevTools → Network tab
// Click pagination link
// Should see: XHR request (tidak ada full page load)
// ✅ AJAX working correctly!
```

