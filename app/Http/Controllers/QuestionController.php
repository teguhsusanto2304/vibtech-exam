<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle =' Question Management';
        // Search filter
        $search = $request->get('search');

        $questions = Question::query()
        ->whereNot('data_status','inactive')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('question_stem', 'like', "%{$search}%")
                ->orWhere('topic', 'like', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->appends(['search' => $search]);

        $usersCount = Question::query()
    // Apply the search filter if a search term exists
    ->when($search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
            $q->where('question_stem', 'like', "%{$search}%")
                ->orWhere('topic', 'like', "%{$search}%");
        });
    })
    // Execute the query and return only the count
    ->count();

        // If AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('admin.questions.table', compact('questions','pageTitle','usersCount'))->render();
        }       

        return view('admin.questions.index', compact('questions','pageTitle','usersCount'));
    }

    public function create(Request $request)
    {
        $pageTitle='Create A New Question';
        return view('admin.questions..form', compact('pageTitle'));
    }

    public function edit($id)
    {
        $question = Question::find($id);
        $pageTitle='Edit Question';
        return view('admin.questions.form', compact('pageTitle','question'));
    }

    public function show($id)
    {
        $pageTitle = 'Question Details';
        // Fetch the question by UUID or fail with 404
        $question = Question::with('exams')->findOrFail($id);

        return view('admin.questions.show', compact('question','pageTitle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_stem' => 'required|string',
            //'topic' => 'required|string',
            //'difficulty_level' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'explanation' => 'nullable|string',
        ]);

        Question::create($validated);

        return redirect()->route('admin.question-banks')->with('success', 'Question created successfully.');
    }

    public function update(Request $request, $id)
    {
        $question = Question::find($id);
        $validated = $request->validate([
            'question_stem' => 'required|string',
            //'topic' => 'required|string',
            //'difficulty_level' => 'required|string|in:easy,medium,hard',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'explanation' => 'nullable|string',
        ]);

        $question->update($validated);

        return redirect()->route('admin.question-banks')->with('success', 'Question updated successfully.');
    }

    public function destroy($id)
    {
        $question = Question::find($id);
        $question->data_status='inactive';
        $question->save();
        return redirect()->back()->with('success', "Question '{$question->question_stem}' deleted sucessfully! ");
    }
}
