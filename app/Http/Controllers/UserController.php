<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserExam;
use App\Models\Exam;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function updatePassword(Request $request) 
    {
        // Validate fields
        $request->validate([
            'current_password' => ['required'],
            'new_password' => [
                'required',
                'confirmed', // requires input "new_password_confirmation"
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ],[
            'new_password.confirmed' => 'New password and confirmation do not match.',
            'new_password.required' => 'The new password field is required.'
        ]);

        $user = Auth::user();

        // Verify current password
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Save new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                //$q->where('name', 'like', "%{$request->search}%")
                //->orWhere('email', 'like', "%{$request->search}%");
                if($request->filled('filterBy'))
                {
                    $q->where($request->get('filterBy'),'like',"%{$request->search}%");
                } else {
                    $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
                }
            });
        }

        // Role filter
        if ($request->filled('role') && in_array($request->role, ['admin', 'user'])) {
            $query->where('role', $request->role);
        }

        // Status filter (active/inactive)
        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('data_status', $request->status);
        }

            if(!$request->filled('search') && !$request->filled('role'))
            {
                $query->where(['role'=>'admin','data_status'=>'active']);
            }



        $users = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.users.table', compact('users'))->render();
        }

        $pageTitle = 'Account Management';

        return view('admin.users.index', compact('users','pageTitle'));
    }



    public function create(Request $request)
    {
        $pageTitle='Create A New User';
        $user = null;
        return view('admin.users.form', compact('pageTitle','user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                function ($attribute, $value, $fail) {
                    // Check if email exists in non-suspended users
                    $existingUser = User::where('email', $value)
                        ->whereIn('data_status', ['active', 'inactive'])
                        ->exists();
                    
                    if ($existingUser) {
                        $fail('The email has already been taken.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
        ]);

        $checkUser = User::where('email',$validated['email'])
            ->where('data_status','suspended')
            ->first();
            if($checkUser)
            {
                $checkUser->update([
                    'name' => $validated['name'],
                    'password' => bcrypt($validated['password']),
                    'role' => 'user',
                    'company' => $validated['company'] ?? null,
                    'data_status' => 'active',
                ]);
                return redirect()->route('admin.users')
            ->with('success', "User '{$checkUser->name}' restored successfully!");
            } else {
                $user = User::create([
                            'name' => $validated['name'],
                            'email' => $validated['email'],
                            'password' => bcrypt($validated['password']),
                            'role' => $validated['role'] ?? 'user', // default to "user" if not provided
                            'company' => $validated['company'] ?? null,
                            'data_status' => 'active',
                        ]);
                        return redirect()->route('admin.users')
            ->with('success', "User '{$user->name}' created successfully!");
            }
        
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
            //'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'], // example extra field
        ]);

        if ($request->filled('password')) {
        // Automatically hashes the password because we're using fill/update
            $validated['password'] = bcrypt($validated['password']);
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
        $exams = Exam::where('data_status','publish')->get();
        return view('admin.users.assign_exam', compact('pageTitle','user','exams'));
    }

    public function saveAssignExam(Request $request,$userId)
    {
        $validated = $request->validate([
            'exam_id' => 'required|string|max:255',
            'active_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:active_date',
        ]);

        // Check for date overlap with other schedules
        $conflict = UserExam::where(['user_id' => $userId])
            ->where(function ($query) use ($validated) {
                //$query->where('active_date', '<=', $validated['end_date'])
                //    ->where('end_date', '>=', $validated['active_date'])
                //    ->where('data_status','pending');
                $query->where('data_status','pending');
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'active_date' => 'The selected date range conflicts with an existing exam schedule.',
            ])->withInput();
        }

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

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        if($user->role==='admin')
        {
            if( User::where(['role'=>'admin','data_status'=>'active'])->count() === 1 )
            {
                return back()->with('error', "User status cannot changed to Inactive.");
            }
        }

        // Toggle status
        $user->data_status = $user->data_status === 'active' ? 'inactive' : 'active';
        $user->save();

        // Optional: add flash message
        return back()->with('success', "User status changed to {$user->data_status}.");
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return back()->withErrors('Error: User not found.');
        }
        $user->update(['data_status' => 'suspended']); // Correctly using 'deleted'

        // Optional: add flash message
        return back()->with('success', "User has deleted.");
    }

    public function updateExam(Request $request, $id)
    {
        $request->validate([
            'active_date' => 'required|date',
            'end_date' => 'required|date|after:active_date',
        ]);

        $exam = \App\Models\UserExam::findOrFail($id);
        $exam->update([
            'active_date' => $request->active_date,
            'end_date' => $request->end_date,
        ]);

        return back()->with('success', 'Exam dates updated successfully.');
    }

    public function removeExam($id)
    {
        $attempt = UserExam::findOrFail($id);
        $attempt->delete();

        return redirect()->back()->with('success', 'Exam attempt removed successfully.');
    }

    public function profile()
    {
        $pageTitle='Profile';
        return view('admin.profile',compact('pageTitle'));
    }

    public function password()
    {
        $pageTitle='Change Password';
        return view('admin.password',compact('pageTitle'));
    }

}