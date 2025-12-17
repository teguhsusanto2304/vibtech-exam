<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;
use App\Models\User;
use App\Models\UserExam;
use App\Notifications\ExamStatusUpdated;
use Illuminate\Support\Facades\Notification;

class ExamController extends Controller
{
    public function indexOld(Request $request)
    {
        $pageTitle ='Exam Management';
        // Search filter
        $search = $request->get('search');

        $exams = Exam::query()
        ->with('examQuestions')
        ->whereNot('data_status','inactive')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->appends(['search' => $search]);

        $usersCount = Exam::query()
    // Apply the search filter if a search term exists
    ->when($search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    })
    // Execute the query and return only the count
    ->count();

        // If AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('admin.exams.table', compact('exams','pageTitle','usersCount'))->render();
        }       

        return view('admin.exams.index', compact('exams','pageTitle','usersCount'));
    }

    public function index(Request $request)
    {
        $pageTitle ='Exam Management';
        // Search filter
        $search = $request->get('search');
        $perPage = $request->get('per_page', 50); // Default 50, can be changed via query param
        $status = $request->get('status', 'published'); // Default tab is published

        // Validate per_page to prevent abuse
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 50;
        
        // Validate status
        $validStatuses = ['publish', 'draft', 'archived'];
        $status = in_array($status, $validStatuses) ? $status : 'publish';

        // Build base query for all tabs
        $buildQuery = function($tabStatus) use ($search) {
            return Exam::query()
                ->with('examQuestions')
                ->where('data_status', $tabStatus)
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                    });
                })
                ->orderBy('created_at', 'desc');
        };

        // Get data for current tab
        $exams = $buildQuery($status)
            ->paginate($perPage, ['*'], 'page')
            ->appends(['search' => $search, 'per_page' => $perPage, 'status' => $status]);

        // Get paginated data for each tab (for display purposes)
        $published = $buildQuery('published')
            ->paginate($perPage, ['*'], 'published')
            ->appends(['search' => $search, 'per_page' => $perPage, 'status' => 'published']);

        $draft = $buildQuery('draft')
            ->paginate($perPage, ['*'], 'draft')
            ->appends(['search' => $search, 'per_page' => $perPage, 'status' => 'draft']);

        $archived = $buildQuery('archived')
            ->paginate($perPage, ['*'], 'archived')
            ->appends(['search' => $search, 'per_page' => $perPage, 'status' => 'archived']);

        // Get total count
        $usersCount = Exam::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->count();

        // If AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('admin.exams.table', compact(
                'exams',
                'pageTitle','usersCount','archived','published','draft','perPage','status'
                ))->render();
        }       

        return view('admin.exams.index', compact(
            'exams','pageTitle','usersCount','archived','published','draft','perPage','status'
        ));
    }

    public function create(Request $request)
    {
        $pageTitle='Create A New Exam';
        $exam = null;
        return view('admin.exams.form', compact('pageTitle','exam'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'questions' => 'required|integer|min:0',
            'duration' => 'required|integer|min:0',
            'data_status' => 'nullable|string|max:50',
            'randomize_options' => 'nullable',
            'randomize_questions' => 'nullable',
            'pass_mark' => 'required|integer|min:30|max:100',
            'instruction' => 'required|min:10'
        ]);

        // ✅ 2. Create exam (UUID handled automatically in the model)
        $exam = Exam::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'questions' => $validated['questions'] ?? 0,
            'duration' => $validated['duration'] ?? 0,
            'data_status' => $validated['data_status'] ?? 'draft',
            'last_modified' => now(),
            'pass_mark' => $validated['pass_mark'],
            'instruction' => $validated['instruction'],
            'randomize_options' => $validated['randomize_options'],
            'randomize_questions' => $validated['randomize_questions']
        ]);

        // Redirect with input
        return redirect()
            ->route('admin.exams.questions', ['id' => $exam->id])
            ->with('formData', $request->all());
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::find($id);
        // 1. Validation: Re-use the exact same validation rules
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'questions' => 'required|integer|min:0',
            'duration' => 'required|integer|min:0',
            // Assuming the hidden input field is named 'data_status'
            'data_status' => 'nullable|string|max:50', 
            'randomize_options' => 'nullable',
            'randomize_questions' => 'nullable',
            'pass_mark' => 'required|integer|min:30|max:100',
            'instruction' => 'required|min:10'
        ]);

        // 2. Prepare data for update
        $dataToUpdate = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'questions' => $validated['questions'] ?? 0,
            'duration' => $validated['duration'] ?? 0,
            'data_status' => $validated['data_status'] ?? 'draft',
            'last_modified' => now(),
            'pass_mark' => $validated['pass_mark'],
            'instruction' => $validated['instruction'],
            
            // Checkbox values: We check if the request explicitly has the field, 
            // otherwise default to 0 (unchecked) due to the hidden input '0' fallback.
            'randomize_options' => $request->has('randomize_options') ? 1 : 0,
            'randomize_questions' => $request->has('randomize_questions') ? 1 : 0,
        ];

        // 3. Update the existing exam model instance
        $exam->update($dataToUpdate);

        // 4. Redirect the user back to the exam questions page with a success message
        return redirect()
            ->route('admin.exams')
            ->with('success', 'Exam "' . $exam->title . '" updated successfully!');
    }

    public function questions(Request $request,$id)
    {
        $exam = Exam::find($id);
        $questions = Question::all();
        $pageTitle = 'Question Manage';
        $examQuestion = ExamQuestion::where('exam_id',$id)->get();

        return view('admin.exams.question', compact('pageTitle','questions','exam','examQuestion'));
    }

    public function assignQuestions(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);
        $maxQuestions = (int) $exam->questions; 

        $questionIds = $request->input('questions', []); // array of UUIDs
        $currentQuestionIds = $exam->examQuestions()->pluck('question_id')->toArray();

        $newQuestionIds = array_diff($questionIds, $currentQuestionIds);

        $currentCount = count($currentQuestionIds);
        $questionsToAdd = count($newQuestionIds);
        $newTotalCount = $currentCount + $questionsToAdd;
        if ($newTotalCount > $maxQuestions) {
            $remainingSlots = $maxQuestions - $currentCount;

            $errorMessage = "Cannot assign questions. The exam is limited to {$maxQuestions} questions. You can only assign {$remainingSlots} more.";

            return redirect()->back()
                ->withInput() 
                ->with('error', $errorMessage);
        }

        // Attach (sync replaces, attach adds)
        //$exam->examQuestions()->attach($questionIds);
        foreach ($newQuestionIds as $qid) {
            ExamQuestion::create([
                'exam_id' => $exam->id,
                'question_id' => $qid,
            ]);
        }

        return redirect()->route('admin.exams.questions', ['id' => $examId,'exam' => $exam])
                        ->with('success', 'Questions assigned successfully!');
    }

    public function removeQuestion($examId, $questionId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->examQuestions()->detach($questionId);

        return redirect()->back()->with('success', 'Question removed.');
    }

    public function clearQuestions($examId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->examQuestions()->detach();

        return redirect()->back()->with('success', 'All questions cleared.');
    }

    public function examUpdateStatus($id,Request $request)
    {
        $exam = Exam::findOrFail($id);
        
        // Check if exam has questions before activating
        if ($request->data_status === 'publish' && $exam->examQuestions()->count() === 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot change status — this exam has no questions assigned.');
        }
        //$userExamCount = UserExam::where(['exam_id'=>$exam->id,'data_status'=>'pending'])->count();
        $userExamCount = UserExam::where('exam_id', $exam->id)
            ->where('data_status', 'pending')
            ->whereHas('user', function ($query) {
                $query->where('data_status', 'active');
            })
            ->count();
        if ($exam->data_status === 'publish' && $request->data_status==='draft' && $userExamCount > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot change status. This exam is currently active (being taken by users).');
        }

        $exam->data_status = $request->data_status;
        $exam->save();
        $status = $exam->data_status.''.($exam->data_status=='archived'?'':'ed');
        if($exam->data_status==='draft')
        {
            return redirect()->route('admin.exams.questions',['id'=>$id])->with('success', 'Exam '.$status.' successfully.');
        } else {
            return redirect()->route('admin.exams')->with('success', 'Exam '.$status.' successfully.');
        }
    }

    public function examChangeStatus($id,Request $request)
    {
        $exam = Exam::findOrFail($id);
        
        // Check if exam has questions before activating
        if ($request->data_status === 'publish' && $exam->examQuestions()->count() === 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot change status — this exam has no questions assigned.');
        }
        //$userExamCount = UserExam::where(['exam_id'=>$exam->id,'data_status'=>'pending'])->count();
        $userExamCount = UserExam::where('exam_id', $exam->id)
            ->where('data_status', 'pending')
            ->whereHas('user', function ($query) {
                $query->where('data_status', 'active');
            })
            ->count();
        if ($exam->data_status === 'publish' && $request->data_status==='draft' && $userExamCount > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot change status. This exam is currently active (being taken by users).');
        }

        $exam->data_status = $request->data_status;
        $exam->save();
        $status = $exam->data_status.''.($exam->data_status=='archived'?'':'ed');
        $status = ($status=='archived'?'deleted':$status);
        return redirect()->route('admin.exams')->with('success', 'Exam '.$status.' successfully.')->with('focus_tab', 'draft');
    }

    public function show($id)
    {
        $exam = Exam::find($id);
        $questions = Question::all();
        $pageTitle = 'Exam Details';
        $examQuestion = ExamQuestion::where('exam_id',$id)->get();

        return view('admin.exams.show', compact('pageTitle','questions','exam','examQuestion'));
    }

    public function edit($id)
    {
        $exam = Exam::find($id);
        $pageTitle = "Edit Exam";
        return view('admin.exams.form', compact('pageTitle','exam'));
    }

    public function destroy($id)
    {
        $exam = Exam::find($id);
        $exam->data_status='archived';
        $exam->save();
        return redirect()->back()->with('success', "Exam '{$exam->title}' deleted sucessfully! ");
    }

    public function examResult(Request $request)
    {
        $userExamId = $request->query('id');
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
        $student = User::find($result->user_id);

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new ExamStatusUpdated($result,$student->name, $status));
        return response()->json([            
            "exam"=>["pass_mark"=>$result->exam->pass_mark,"scores"=> $result->scores,
            "total_questions"=> $result->exam->questions,"attempts_used"=>$result->attempts_used],
            "correctCount"=>$correctCount,
            "status"=>$status]);
    }

}
