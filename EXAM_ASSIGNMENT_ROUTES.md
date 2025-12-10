# Routes untuk Exam Assignment & Deadline Management

Tambahkan routes berikut ke dalam file `routes/api.php`:

```php
// Exam Assignment Routes (Protected - Admin Only)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Assign exam to user
    Route::post('/exam-assignments/assign', [ExamAssignmentController::class, 'assignExamToUser']);
    
    // Get admin dashboard dengan pending exams
    Route::get('/exam-assignments/dashboard', [ExamAssignmentController::class, 'adminDashboard']);
    
    // Get pending exams untuk specific user
    Route::get('/exam-assignments/user/{userId}/pending', [ExamAssignmentController::class, 'getUserPendingExams']);
    
    // Reset notification flag
    Route::post('/exam-assignments/{userExamId}/reset-notification', [ExamAssignmentController::class, 'resetNotificationFlag']);
    
    // Extend deadline
    Route::put('/exam-assignments/{userExamId}/extend-deadline', [ExamAssignmentController::class, 'extendDeadline']);
    
    // Cancel assignment
    Route::post('/exam-assignments/{userExamId}/cancel', [ExamAssignmentController::class, 'cancelAssignment']);
});

// User pending exams (User dapat check exam mereka sendiri)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/my-exams/pending', [ExamAssignmentController::class, 'getUserPendingExams']);
});
```

## 🧪 Testing Endpoints dengan cURL

### 1. Assign Exam to User
```bash
curl -X POST http://localhost:8000/api/exam-assignments/assign \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 5,
    "exam_id": 2,
    "days_to_complete": 7
  }'
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user_exam_id": 15,
        "user": {
            "id": 5,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "exam": {
            "id": 2,
            "title": "Math Final Exam"
        },
        "deadline": "2025-12-13 10:30:00",
        "days_to_complete": 7
    },
    "message": "Exam assigned to John Doe with deadline in 7 days."
}
```

### 2. Get Admin Dashboard
```bash
curl -X GET http://localhost:8000/api/exam-assignments/dashboard \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

**Response:**
```json
{
    "success": true,
    "data": {
        "total_pending": 10,
        "total_overdue": 2,
        "total_approaching_deadline": 3,
        "pending_exams": [...],
        "overdue_exams": [...],
        "approaching_deadline": [...]
    }
}
```

### 3. Get User Pending Exams
```bash
curl -X GET http://localhost:8000/api/exam-assignments/user/5/pending \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 4. Extend Deadline
```bash
curl -X PUT http://localhost:8000/api/exam-assignments/15/extend-deadline \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "additional_days": 3
  }'
```

### 5. Cancel Assignment
```bash
curl -X POST http://localhost:8000/api/exam-assignments/15/cancel \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "reason": "User tidak ada kerja sama"
  }'
```

### 6. Reset Notification Flag
```bash
curl -X POST http://localhost:8000/api/exam-assignments/15/reset-notification \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

## 💻 Middleware Admin Check

Buat middleware untuk check admin role di `app/Http/Middleware/AdminOnly.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }
}
```

Daftarkan di `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ...
    'admin' => \App\Http\Middleware\AdminOnly::class,
];
```

## 📊 Database Seeding (Optional)

Buat seeder untuk testing di `database/seeders/ExamAssignmentSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserExam;
use App\Models\User;
use App\Models\Exam;
use Carbon\Carbon;

class ExamAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'admin')->limit(5)->get();
        $exams = Exam::where('data_status', 'active')->limit(3)->get();

        foreach ($users as $user) {
            foreach ($exams as $exam) {
                // 1 pending exam
                UserExam::create([
                    'user_id' => $user->id,
                    'exam_id' => $exam->id,
                    'data_status' => 'pending',
                    'scheduled_deadline' => now()->addDays(rand(1, 14)),
                    'notification_sent' => false,
                    'attempts_used' => 0,
                ]);

                // 1 completed exam
                UserExam::create([
                    'user_id' => $user->id,
                    'exam_id' => $exam->id,
                    'data_status' => 'passed',
                    'scheduled_deadline' => now()->subDays(rand(1, 30)),
                    'notification_sent' => true,
                    'attempts_used' => 2,
                    'scores' => rand(70, 100),
                    'finished_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
```

Run:
```bash
php artisan db:seed --class=ExamAssignmentSeeder
```

---

**Last Updated:** December 6, 2025

