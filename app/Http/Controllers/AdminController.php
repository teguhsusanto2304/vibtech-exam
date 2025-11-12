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
            return redirect()->intended('dashboard')->with('success', 'Welcome back!');
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

        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }

    public function generalLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if(Auth::user()->role=='admin')
            {
                return redirect()->intended('admin/dashboard')->with('success', 'Welcome back!');
            } else if(Auth::user()->role=='user') {
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



                return redirect()->intended('dashboard');
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
            return redirect()->intended('admin/dashboard')->with('success', 'Welcome back!');
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
}
