<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\UserExam;
use App\Models\ExamQuestion;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $exams = Exam::where('data_status', 'active')->get(['id', 'title', 'duration']);
        return response()->json($exams);
    }

    public function show($id)
    {
        $exam = Exam::findOrFail($id);
        return response()->json($exam);
    }

    public function questions($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);
        return response()->json($exam->questions);
    }

    public function detail(Request $request)
    {
        $exams  = UserExam::with('exam')->where('user_id', auth()->id())->get(); 

        if ($exams->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No exam found for this user.',
            ], 404);
        }

        $arrExam = [];

        foreach ($exams as $row) {
            $arrExam[] = [
                'exam_title'    => $row->exam->title,
                'questions'     => $row->exam->questions,
                'description'   => $row->exam->description,
                'pass_mark'     => $row->exam->pass_mark,
                'duration'      => $row->exam->duration,
                'attempt_used'  => $row->attempts_used,
                'instruction'   => $row->exam->instruction,
                'exam_id'       => $row->exam->id,
                'user_exam_id'  => $row->id
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $arrExam
        ], 200);

    }


    public function getQuizQuestion($id)
    {
        $userId = auth()->id();
        $user_exam_id = $id;

        // Get the exam only if it belongs to logged user
        $userExam = UserExam::with('exam.masterExamQuestions')
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
            $userExam->attempts_used += 1;
            $userExam->started_at = now();
            $userExam->save();

            $userAnswers = UserAnswer::where(['user_exam_id'=>$user_exam_id,'attempts_used'=>$userExam->attempts_used])->get();
            foreach($userAnswers as $answer){
                    DB::table('user_answers_history')->insert([
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
            UserAnswer::where('user_exam_id', $user_exam_id)->delete();

        }

        if ((int) $userExam->attempts_used === 3 ) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached the maximum number of attempts.',
                'remaining_attempts' => 0
            ], 403);
        }


        // Format questions into desired structure
        $formattedQuestions = $userExam->exam->masterExamQuestions->map(function ($q) {
            $correct_key = strtolower($q->question->correct_option); // Mengubah 'A' menjadi 'a'
            $column_name = 'option_' . $correct_key;
            return [
                'id' => $q->id,
                'question' => $q->question,
                'options' => [
                    'A' => $q->option_a,
                    'B' => $q->option_b,
                    'C' => $q->option_c,
                    'D' => $q->option_d,
                ],
                'correct' => $q->question->{$column_name},
                'explanation' => $q->question->explanation ?? null
            ];
        }); 

        return response()->json([
            'success' => true,
            'data' => $formattedQuestions
        ]);
    }


    public function submitAnswer(Request $request, $id)
    {
        $eq = ExamQuestion::with('question')->find($request->question_id);

        if (!$eq) {
            return response()->json([
                'success' => false,
                'message' => 'Question not found'
            ], 404);
        }

        $correct_key = strtolower($eq->question->correct_option); // Mengubah 'A' menjadi 'a'
        $column_name = 'option_' . $correct_key;

        //$isCorrect = ($request->selected_option == $question->correct_option);
        $isCorrect = (trim($request->selected_option) == trim($eq->question->{$column_name}));

        // store user answer (optional)
        UserAnswer::create(
            [
                'user_exam_id' => $id,
                'exam_question_id' => $request->question_id,
                'user_option'  => trim($request->selected_option),
                'is_correct'      => $isCorrect,
            ]
        ); 

        $userExam = UserExam::with(['exam', 'answers'])
            ->where('id', $id)
            ->first();

        $correctCount = $userExam->answers->filter(function ($answer) {
            return $answer->is_correct==true;
        })->count();

        $score = $userExam->exam->questions > 0 
            ? round(($correctCount / $userExam->exam->questions) * 100)
            : 0;
        if ($userExam->attempts_used < 3)
            {
                $status = $score >= $userExam->exam->pass_mark ? 'passed' : 'pending';
            } else {
                $status = $score >= $userExam->exam->pass_mark ? 'passed' : 'cancel';
            }
        
        $userExam->scores = $score;
        $userExam->data_status = $status;
        $userExam->finished_at = now();
        $userExam->save();

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            //'correct_option' => $question->correct_option,
            'message' => $isCorrect ? 'Correct answer' : 'Incorrect answer',
        ]);
    }

    public function examResult($userExamId)
    {
        $result = UserExam::with(['exam', 'answers.examQuestion'])
            ->where('id', $userExamId)
            ->first();

        $correctCount = $result->answers->filter(function ($answer) {
            return $answer->is_correct==true;
        })->count();

        if (!$result) {
            return response()->json([
                'message' => 'Result not found'
            ], 404);
        }

        $status = $result->scores >= $result->exam->pass_mark ? 'passed' : 'cancel';

        // Format response JSON for frontend
        /**return response()->json([
            'exam_id' => $result->id,
            'exam_title' => $result->exam->title,
            'score' => $result->score,
            'total_questions' => $result->exam->examQuestion->count()
        ]);**/
        return response()->json([            
            "exam"=>["pass_mark"=>$result->exam->pass_mark,"scores"=> $result->scores,
            "total_questions"=> $result->exam->questions,"attempts_used"=>$result->attempts_used],
            "correctCount"=>$correctCount,
            "status"=>$status]);
    }
}
