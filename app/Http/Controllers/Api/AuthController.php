<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserExam;
use Illuminate\Support\Str;

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

        $exams  = UserExam::with('exam')->where('user_id', auth()->id())->first(); 

        if (!$exams) {
            return response()->json([
                'success' => false,
                'message' => 'No exam found for this user.',
            ], 404);
        } else {
            if($exams->data_status=='passed' || $exams->data_status=='cancel') 
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
        
        // 3. Buat Token
        $token = $user->createToken('auth_token')->plainTextToken;

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
        $request->user()->tokens()->delete();
        $exams  = UserExam::with('exam')->where('user_id', auth()->id())->get(); 
        $exams->finished_at = date('Y-m-d H:i:s');
        $exams->save();

        return response()->json(['message' => 'Logged out']);
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
