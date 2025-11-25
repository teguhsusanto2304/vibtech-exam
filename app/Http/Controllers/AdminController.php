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
        $users = User::where(['role'=>'user','data_status'=>'active'])->count();
        $inactive_users = User::where(['role'=>'user','data_status'=>'inactive'])->count();
        return view('admin.dashboard',compact('users','inactive_users'));
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
