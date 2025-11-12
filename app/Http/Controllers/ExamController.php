<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;

class ExamController extends Controller
{
    public function index(Request $request)
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
        $exam->data_status = $request->data_status;
        $exam->save();
        $status = $exam->data_status.''.($exam->data_status=='pending'?'':'ed');
        return redirect()->route('admin.exams.questions',['id'=>$id])->with('success', 'Exam '.$status.' successfully.');
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
        $exam->data_status='inactive';
        $exam->save();
        return redirect()->back()->with('success', "Exam '{$exam->title}' deleted sucessfully! ");
    }

}
