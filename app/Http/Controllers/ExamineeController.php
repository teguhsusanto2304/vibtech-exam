<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserExam;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;
use App\Models\UserAnswer;

class ExamineeController extends Controller
{
    public function showLoginForm()
    {
        return view('examinee.login');
    }

    public function loginOl(Request $request)
{
    // Validate form input
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Attempt login
    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        // ✅ Get the authenticated user
        $user = Auth::user();

        // ✅ Check if user has any assigned examinations
        $hasExam = UserExam::where('user_id', $user->id)->exists();

        if (! $hasExam) {
            // If no assigned exam, logout immediately
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'You are not assigned to any examination.',
            ])->onlyInput('email');
        }

        // ✅ If user has assigned exams, continue login
        return redirect()->intended(route('dashboard'))
            ->with('success', 'Welcome back!');
    }

    // Failed login
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    public function login(Request $request)
    {
        // Validate form input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $credentials['role'] = 'user';

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();
            $user = Auth::user();

        // ✅ Check if user has any assigned examinations
            $hasExam = UserExam::where('user_id', $user->id)
            ->whereDate('active_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();

        if (! $hasExam) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'You are not authorized to login because you have no active examination. ',
            ])->onlyInput('email');
        }

            // Redirect to intended page or dashboard
            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back!');
        }

        // Failed login
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    public function dashboard()
    {
        $exams  = UserExam::with('exam')->where('user_id',auth()->user()->id)->get();
        
        $arrExam = [];
        foreach ($exams as $row) {
            $arrExam['title'] = $row->exam->title; // Access the related Exam model
            $arrExam['questions'] = $row->exam->questions;
            $arrExam['description'] = $row->exam->description;            
            $arrExam['pass_mark'] = $row->exam->pass_mark;
            $arrExam['duration'] = $row->exam->duration;
            $arrExam['attempt_used'] = $row->attempts_used;
            $arrExam['instruction'] = $row->exam->instruction;
            $arrExam['examId'] = $row->exam->id;
            session([
                'exam_duration' => $row->exam->duration,
                'exam_id'       => $row->exam->id,
            ]);
        }
        
        return view('examinee.dashboard',compact('exams','arrExam'));
    }

    public function startExam($examId)
    {
        $userExam = UserExam::with('exam')->where(['user_id'=> auth()->id(),'data_status'=>'pending'])->firstOrFail();
        $userExam->started_at = now();
        $userExam->save();
        $del = UserAnswer::where('user_exam_id',$userExam->id);
        $del->delete();
        
        foreach ($userExam as $row) {
                session([
                'exam_duration' => $userExam->exam->duration,
                'exam_id'       => $userExam->exam->id,
                'exam_start_time' => now(), // Save the start timestamp
            ]);
        }

        // Calculate remaining time
        $remainingSeconds = $this->calculateRemainingTime();

        // Redirect to exam page with remaining time
        return redirect()->route('exam', [
            'examId' => $examId,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    private function calculateRemainingTime()
    {
        $durationMinutes = session('exam_duration');
        $startTime = session('exam_start_time');

        if (!$startTime || !$durationMinutes) {
            return 0;
        }

        $elapsed = now()->diffInSeconds($startTime);
        $remaining = max(($durationMinutes * 60) - $elapsed, 0);

        return $remaining;
    }

    public function exam($examId)
    {
        $userExam = UserExam::with('exam')->where('user_id', auth()->id())->firstOrFail();

        // Get exam questions with question details
        $questions = ExamQuestion::with('question')
            ->where('exam_id', $userExam->exam_id)
            ->first();// limit 1 question per page

            $question = Question::findOrFail($questions->question_id);

            // Put options into an array
            $options = [
                'A' => $question->option_a,
                'B' => $question->option_b,
                'C' => $question->option_c,
                'D' => $question->option_d,
            ];

            // Shuffle options randomly (preserving keys)
            $shuffledOptions = collect($options)->shuffle();

            $exam = Exam::with('masterExamQuestions.question')->findOrFail($examId);
            $remainingSeconds = $this->calculateRemainingTime();


        //$examQuestions = $userExam->exam->masterExamQuestions;
        return view('examinee.exam',compact('exam','userExam', 'question','shuffledOptions','remainingSeconds'));
    }

    public function questions($examId)
    {
        $exam = Exam::with('masterExamQuestions.question')->findOrFail($examId);

        $questions = $exam->masterExamQuestions->map(function ($eq) {
            return [
                'id' => $eq->question->id,
                'question' => $eq->question->question_stem,
                'options' => [
                    'A' => $eq->question->option_a,
                    'B' => $eq->question->option_b,
                    'C' => $eq->question->option_c,
                    'D' => $eq->question->option_d,
                ],
                'correct' => $eq->question->correct_option,
                'explanation' => $eq->question->explanation,
            ];
        });

        return response()->json($questions);
    }

    public function storeAnswer(Request $request, $examId)
    {
        $validated = $request->validate([
            'question_id' => 'required',
            'selected_option' => 'required',
        ]);
        $question = Question::findOrFail($validated['question_id']);
        $isCorrect = $validated['selected_option'] === $question->correct_option;
        $userExam = UserExam::where(['exam_id'=>$examId,'user_id'=>auth()->user()->id])->first();
        $examQuestion = ExamQuestion::where(['exam_id'=>$examId,'question_id'=>$validated['question_id']])->first();
        UserAnswer::updateOrCreate(
            [
                'user_exam_id' => $userExam->id,
                'exam_question_id' => $examQuestion->id,
                'user_option' => $validated['selected_option'],
                'is_correct' => $isCorrect
            ],
            [
                'user_option' => $validated['selected_option'],
                'is_correct' => $isCorrect
            ]
        );

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
        ]);
    }

    public function completeExam()
    {
        $userExam = UserExam::with('exam','answers')->where(['user_id'=> auth()->id(),'data_status'=>'pending'])->firstOrFail();
        
        $correctAnswers = $userExam->answers->where('is_correct', true);
        $score = ($correctAnswers->count()/$userExam->exam->questions)*100;
        $userExam->finished_at = now();
        $userExam->scores = $score;
        $userExam->attempts_used = $userExam->attempts_used+1;
        $userExam->save();
        return redirect()->route('done');
    }

    public function done()
    {
        $userExam = UserExam::with('exam','answers')->where(['user_id'=> auth()->id(),'data_status'=>'pending'])->firstOrFail();
        
        $correctAnswers = $userExam->answers->where('is_correct', true);
        // Count how many are correct
        $correctCount = $correctAnswers->count();
        $score = ($correctAnswers->count()/$userExam->exam->questions)*100;
        if ($userExam->scores >= $userExam->exam->pass_mark) {
            $status = 'passed';
        } elseif ($userExam->attempts_used >= 3 && $userExam->scores < $userExam->exam->pass_mark) {
            $status = 'failed_max';
        } else {
            $status = 'failed';
        }

        return view('examinee.done',compact('userExam','correctCount','status'));
    }
}
