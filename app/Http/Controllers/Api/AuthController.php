<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserExam;
use Illuminate\Support\Str;

//php artisan optimize:clear
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 1. Otentikasi
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if(Auth::user()->data_status==='inactive')
        {
            return response()->json([
                'success' => false,
                'message' => 'The user account has been inactivated.',
            ], 401);
        }

        //$exams  = UserExam::with('exam')->where('user_id', auth()->id())->first(); 
        $exams = UserExam::with('exam')->where('user_id', Auth::user()->id)
                    ->where('active_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->where('data_status','pending')
                    ->orderBy('created_at','DESC')
                    ->first();

        if (!$exams) {
            return response()->json([
                'success' => false,
                'message' => 'No exam found for this user.',
            ], 404);
        } else {
            if($exams->exam->data_status!='publish')
            {
                return response()->json([
                    'success' => false,
                    'message' => 'No exam found for this user.',
                ], 404);
            }
            if($exams->data_status=='passed' || $exams->data_status=='cancel' || $exams->attempts_used ==3) 
            {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to login because you have no active examination.'
                ], 401);
            }
        }
        
        // 2. Dapatkan objek User yang terotentikasi
        // User() adalah metode di Auth, BUKAN di Query Builder.
        // Anda tidak dapat menggunakan select() di sini.
        $user = Auth::user(); 
        $userEdit = auth()->user();
        $userEdit->attempts_used=$exams->attempts_used;
        $userEdit->save();
        
        // 3. Buat Token
        $token = $user->createToken('auth_token')->plainTextToken;

        //add $exams->attempts_used set on session

        // 4. Kirim respons dengan data user yang difilter
        return response()->json([
            'success' => true,
            'user' => [ // Filter data secara manual di sini, atau pakai API Resource
                'name' => $user->name,
                'email' => $user->email,
                'company' => $user->company,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request)
    {
        //$request->user()->tokens()->delete();
        //$exams  = UserExam::with('exam')->where('user_id', auth()->id())->get(); 
        //$exams->finished_at = now();
        //$exams->save();

        if ($request->user()) {
        // Hapus token jika user ditemukan
            session()->flush();
            $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Logged out']);
        }
        return response()->json(['message' => 'Unauthorized / No active session'], 401); 
    }

    public function logCheat(Request $request, $examId)
    {
        $user = auth()->user();

        // Log cheat to DB
        \DB::table('exam_cheating_logs')->insert([
            'id' => (string) Str::uuid(),
            'user_id'  => $user->id,
            'user_exam_id'  => $examId,
            'event'    => $request->event ?? 'Unknown',
            'created_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cheating attempt logged.'
        ]);
    }

}
