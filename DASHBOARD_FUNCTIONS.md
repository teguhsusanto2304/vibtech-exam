# Dashboard Functions Documentation

## Overview
This document describes the dashboard functions created in `AdminController.php` to display data in the admin dashboard blade template.

## Functions Created

### `dashboard()` - Main Dashboard Function
**Location:** `app/Http/Controllers/AdminController.php`

**Purpose:** Fetches and returns all necessary data for displaying the admin dashboard with statistics and exam lists.

**Data Returned:**

1. **Statistics Cards:**
   - `$totalUsers` - Count of active users with role 'user' and status 'active'
   - `$activeExams` - Count of published exams (data_status = 'publish')
   - `$totalQuestions` - Count of published questions (data_status = 'publish')

2. **On-going Exams Table:**
   - `$ongoingExams` - UserExam records with status 'started', includes:
     - User information (name, email)
     - Exam information (title)
     - Sorted by latest started_at
     - Limited to 10 records

3. **Completed Exams Table:**
   - `$completedExams` - UserExam records with status 'completed', includes:
     - User information (name, email)
     - Exam information (title)
     - Completion date (finished_at)
     - Sorted by latest finished_at
     - Limited to 10 records

4. **Not Yet Started Exams Table:**
   - `$notStartedExams` - UserExam records with status 'pending', includes:
     - User information (name, email)
     - Exam information (title)
     - Assignment date (created_at)
     - Sorted by latest created_at
     - Limited to 10 records

## Database Relationships Used

- **UserExam Model:**
  - Belongs to User (with eager loading)
  - Belongs to Exam (with eager loading)
  - Has many UserAnswers

- **User Model:**
  - Has many UserExams

- **Exam Model:**
  - Has many ExamQuestions
  - Belongs to many Questions

## Blade Template Updates

The following updates were made to `resources/views/admin/dashboard.blade.php`:

### 1. Statistics Cards
- Changed hardcoded values to dynamic data using `{{ number_format() }}` helper
- Now displays real counts from database

### 2. On-going Exams Table
- Replaced hardcoded rows with `@forelse` loop
- Displays `$ongoingExams` data dynamically
- Shows "No on-going exams" message when empty

### 3. Completed Exams Table
- Replaced hardcoded rows with `@forelse` loop
- Displays `$completedExams` data dynamically
- Formats completion date using `->format('Y-m-d')`
- Shows "No completed exams" message when empty

### 4. Not Yet Started Exams Table
- Replaced hardcoded rows with `@forelse` loop
- Displays `$notStartedExams` data dynamically
- Formats assignment date using `->format('Y-m-d')`
- Shows "No pending exams" message when empty

## Query Optimization

All queries use:
- **Eager Loading:** `with(['user', 'exam'])` to prevent N+1 query problems
- **Filtering:** Status-based filtering for relevant data
- **Sorting:** Latest records first using `latest()`
- **Pagination:** Limited to 10 records per section

## Usage

The dashboard is accessible via the route:
```php
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
```

Simply navigate to `/admin/dashboard` to see the populated dashboard with real data from the database.

## Future Enhancements

Possible improvements:
- Add search functionality for exam tables
- Add date range filtering
- Add pagination for tables showing more than 10 records
- Add export to CSV functionality
- Add charts/graphs for statistics
