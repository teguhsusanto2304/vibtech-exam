<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserExam;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function submit($examId, Request $request)
    {
        $validated = $request->validate([
            'answers' => 'required|array'
        ]);

        // Example: scoring logic
        $score = collect($validated['answers'])->filter(fn($a) => $a['is_correct'])->count();

        UserExam::create([
            'user_id' => $request->user()->id,
            'exam_id' => $examId,
            'data_score' => $score,
            'attempts_used' => 1
        ]);

        return response()->json(['message' => 'Exam submitted successfully', 'score' => $score]);
    }
}
