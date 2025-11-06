<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserExam;
use App\Models\Exam;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->paginate(10);

        // Return partial view for AJAX
        if ($request->ajax()) {
            return view('admin.users.table', compact('users'))->render();
        }

        return view('admin.users.index', compact('users'));
    }


    public function create(Request $request)
    {
        $pageTitle='Create A New User';
        return view('admin.users.form', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'], // example extra field
        ]);

        // ✅ 2. Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user', // default to "user" if not provided
            'company' => $validated['company'] ?? null,
        ]);

        // ✅ 3. Redirect or return response
        return redirect()->route('admin.users')
            ->with('success', "User '{$user->name}' created successfully!");
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }
        $validated = $request->validate([
           'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'], // example extra field
        ]);

        if ($request->filled('password')) {
        // Automatically hashes the password because we're using fill/update
            $user->password = bcrypt($validated['password']); 
            // You can also use: $validated['password'] = bcrypt($validated['password']);
        } else {
            // Remove the password key from the validated array so it's not set to null
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function show($id)
    {
        $user = User::find($id);
        $userExams = UserExam::with([
            'exam.masterExamQuestions.question',  // All questions in this exam
            'answers.examQuestion.question'       // User answers with their questions
        ])
        ->where('user_id', $id)
        ->latest()
        ->get();
        $pageTitle='Profile and History';
        return view('admin.users.show', compact('pageTitle','user','userExams'));
    }

    public function assignExam($id)
    {
        $user = User::find($id);
        $pageTitle='Assign a examination';
        $exams = Exam::all();
        return view('admin.users.assign_exam', compact('pageTitle','user','exams'));
    }

    public function saveAssignExam(Request $request,$userId)
    {
        $validated = $request->validate([
            'exam_id' => 'required|string|max:255',
            'active_date' => 'required|string',
            'end_date' => 'required|string'
        ]);

        $exam = Exam::find($request->exam_id);

        $userExam = UserExam::create([
            'user_id' => $userId,
            'exam_id' => $request->exam_id,
            'end_date' => $request->end_date,
            'data_status' => 'pending',
            'active_date' => $request->active_date,
            'duration' => $exam->duration
        ]);
        return redirect()
            ->route('admin.users.show', ['id' => $userId])
            ->with('success', 'User exam created successfully!');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $pageTitle='Edit User';
        return view('admin.users.form', compact('pageTitle','user'));
    }
}