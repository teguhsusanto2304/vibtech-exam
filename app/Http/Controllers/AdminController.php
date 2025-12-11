<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserExam;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function actlogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->data_status=='inactive' && Auth::user()->rolee=='user') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You are not authorized to log in because you have no active on your account.',
                    ])->onlyInput('email');
                
            }
            if(Auth::user()->role=='admin')
            {
                dd(Auth::user()->role);
                //return redirect()->intended('admin/dashboard')->with('success', 'Welcome back!');
                return redirect()->intended('admin/users')->with('success', 'Welcome back!');
            } else if(Auth::user()->role=='userx') {
                $hasExam = UserExam::with('exam')->where('user_id', Auth::user()->id)
                    ->whereDate('active_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->first(); // ✅ get the model, not a boolean

                if (! $hasExam) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You are not authorized to log in because you have no active examination.',
                    ])->onlyInput('email');
                
                }

                if ((int) $hasExam->attempts_used === 3) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You have reached the maximum number of exam attempts. Your account has been locked from further exams.',
                    ])->onlyInput('email');
                } 
                else if ((int) $hasExam->attempts_used < 3 && $hasExam->scores > $hasExam->exam->pass_mark) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You have already passed the exam successfully. Further attempts are not allowed.',
                    ])->onlyInput('email');
                }

                return redirect()->intended('');
            }
            
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    public function generalLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->data_status=='inactive' && Auth::user()->rolee=='user') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You are not authorized to log in because you have no active on your account.',
                    ])->onlyInput('email');
                
            }
            if(Auth::user()->role=='admin')
            {
                dd(Auth::user()->role);
                return redirect()->intended('admin/dashboard')->with('success', 'Welcome back!');
            } else if(Auth::user()->role=='userx') {
                $hasExam = UserExam::with('exam')->where('user_id', Auth::user()->id)
                    ->whereDate('active_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->first(); // ✅ get the model, not a boolean

                if (! $hasExam) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You are not authorized to log in because you have no active examination.',
                    ])->onlyInput('email');
                
                }

                if ((int) $hasExam->attempts_used === 3) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You have reached the maximum number of exam attempts. Your account has been locked from further exams.',
                    ])->onlyInput('email');
                } 
                else if ((int) $hasExam->attempts_used < 3 && $hasExam->scores > $hasExam->exam->pass_mark) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You have already passed the exam successfully. Further attempts are not allowed.',
                    ])->onlyInput('email');
                }



                return redirect()->intended('');
            }
            
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function dologin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $credentials['role'] = 'admin';

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->data_status=='inactive' && Auth::user()->rolee=='user') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You are not authorized to log in because you have no active on your account.',
                    ])->onlyInput('email');
                
            }
            if(Auth::user()->role=='admin')
            {
                if(Auth::user()->data_status=='active')
                {
                    return redirect()->intended('admin/users')->with('success', 'Welcome back!');
                } else {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You are not authorized to log in because you have no active on your account.',
                    ])->onlyInput('email');
                }
                
            } else if(Auth::user()->role=='userx') {
                $hasExam = UserExam::with('exam')->where('user_id', Auth::user()->id)
                    ->whereDate('active_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->first(); // ✅ get the model, not a boolean

                if (! $hasExam) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You are not authorized to log in because you have no active examination.',
                    ])->onlyInput('email');
                
                }

                if ((int) $hasExam->attempts_used === 3) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You have reached the maximum number of exam attempts. Your account has been locked from further exams.',
                    ])->onlyInput('email');
                } 
                else if ((int) $hasExam->attempts_used < 3 && $hasExam->scores > $hasExam->exam->pass_mark) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You have already passed the exam successfully. Further attempts are not allowed.',
                    ])->onlyInput('email');
                }



                return redirect()->intended('');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        
        // Dashboard statistics - Total (All time)
        $totalUsers = User::where(['role'=>'user','data_status'=>'active'])->count();
        $inactiveUsers = User::where(['role'=>'user','data_status'=>'inactive'])->count();
        
        // Get active exams count
        $activeExams = \App\Models\Exam::where('data_status', 'publish')->count();
        
        // Get total questions count
        $totalQuestions = \App\Models\Question::where('data_status', 'active')->count();
        
        // Statistics for current month
        $thisMonthUsers = User::where(['role'=>'user','data_status'=>'active'])
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        
        $thisMonthCompletedExams = UserExam::whereIn('data_status', ['passed','cancel'])
            ->whereMonth('finished_at', $currentMonth)
            ->whereYear('finished_at', $currentYear)
            ->count();
        
        $thisMonthOngoingExams = UserExam::where('data_status', 'pending')
            ->where('attempts_used', '>', 0)
            ->whereMonth('started_at', $currentMonth)
            ->whereYear('started_at', $currentYear)
            ->count();
        
        // Get on-going exams with user details (filtered by current month)
        $ongoingExams = UserExam::with(['user', 'exam'])
            ->where('data_status', 'pending')
            ->where('attempts_used', '>', 0)
            ->whereBetween('started_at', [$monthStart, $monthEnd])
            ->latest('started_at')
            ->limit(10)
            ->get();
        
        // Get completed exams with user details (filtered by current month)
        $completedExams = UserExam::with(['user', 'exam'])
            ->whereIn('data_status', ['passed','cancel'])
            ->whereBetween('finished_at', [$monthStart, $monthEnd])
            ->latest('finished_at')
            ->limit(10)
            ->get();
        
        // Get not yet started exams with user details (filtered by current month)
        $notStartedExams = UserExam::with(['user', 'exam'])
            ->where('data_status', 'pending')
            ->where('attempts_used', 0)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->latest('created_at')
            ->limit(10)
            ->get();
        
        // Format current month and year for display
        $currentMonthYear = now()->format('F Y');
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'inactiveUsers',
            'activeExams',
            'totalQuestions',
            'thisMonthUsers',
            'thisMonthCompletedExams',
            'thisMonthOngoingExams',
            'ongoingExams',
            'completedExams',
            'notStartedExams',
            'currentMonthYear'
        ));

    }

    

    public function questionBanks()
    {
        return view('admin.question_banks');
    }

    public function getQuizQuestion(Request $request,$id)
    {
        $userId = $request->query('userId');
        $user_exam_id = $id;

        // Get the exam only if it belongs to logged user
        $userExam = UserExam::with('exam.masterExamQuestions','answers')
            ->where('id', $user_exam_id)
            ->where('user_id', $userId)
            ->first();

        if (!$userExam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found or not assigned to this user.',
            ], 404);
        }

        // If no start time = new attempt
        if ((int) $userExam->attempts_used < 3 && $userExam->data_status!='passed') {
            \App\Models\UserAnswer::where('user_exam_id', $user_exam_id)->delete();
            if($userExam->exam->questions === $userExam->answers->count() )
                {
                    //$userExam->attempts_used += 1;
                    //$userExam->save();
                } else if($userExam->answers->count() === 0) {
                    $userExam->started_at = now();
                    $userExam->attempts_used += 1;
                    $userExam->save();
                }
            $userAnswers = \App\Models\UserAnswer::where(['user_exam_id'=>$user_exam_id,'attempts_used'=>$userExam->attempts_used])->get();
            foreach($userAnswers as $answer){
                    \DB::table('user_answers_history')->insert([
                        'id' => $answer->id,
                        'user_exam_id' => $answer->user_exam_id,
                        'exam_question_id' => $answer->exam_question_id,
                        'user_option' => $answer->user_option,
                        'is_correct' => $answer->is_correct,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'attempts_used' => $userExam->attempts_used,
                    ]);
            }

             return response()->json([
                'success' => true,
                'questions' => $userExam->exam->questions,
                'count_of_answer'=>$userExam->answers->count()
            ], 404);
            //UserAnswer::where('user_exam_id', $user_exam_id)->delete();

        } else {
            return response()->json([
                'success' => false,
                'questions' => $userExam->exam->questions,
                'count_of_answer'=>$userExam->answers->count()
            ], 404);
        }
    }
}
