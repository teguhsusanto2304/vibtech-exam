@extends('layouts.examinee.app-exam')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-lg mt-10">
    <div class="flex justify-between items-center border-b pb-3 mb-4">
        <h2 class="text-lg font-bold">
            Q <span id="current-question">1</span> of <span id="total-questions">0</span>
        </h2>
        <div class="text-right">
            <p class="text-gray-600 text-sm">Time Remaining</p>
            <div class="flex space-x-3 font-bold text-lg">
                <div><span id="hours">0</span> <small class="text-xs">Hours</small></div>
                <div><span id="minutes">0</span> <small class="text-xs">Minutes</small></div>
                <div><span id="seconds">0</span> <small class="text-xs">Seconds</small></div>
            </div>
        </div>
    </div>

    <div id="question-container" class="mt-4">
        <h3 id="question-text" class="text-xl font-semibold mb-3"></h3>
        <form id="options-form" class="space-y-2"></form>
    </div>

    <div id="feedback" class="hidden mt-4 p-3 rounded-md"></div>
    <div id="explanation" class="hidden mt-3 p-3 rounded-md border border-blue-300 bg-blue-50 text-blue-800"></div>

    <div class="flex justify-end mt-6">
        <button id="next-btn"
            class="bg-indigo-900 hover:bg-indigo-800 text-white px-5 py-2 rounded-md font-semibold">
            Next Question
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const examId = "{{ $exam->id }}";
    const res = await fetch(`/exam/${examId}/questions`);
    const questions = await res.json();

    let current = 0;
    const total = questions.length;
    document.getElementById('total-questions').textContent = total;

    const questionEl = document.getElementById('question-text');
    const formEl = document.getElementById('options-form');
    const feedbackEl = document.getElementById('feedback');
    const explanationEl = document.getElementById('explanation');
    const nextBtn = document.getElementById('next-btn');

    // ✅ Create a "Submit" button
    const submitBtn = document.createElement('button');
    submitBtn.id = 'submit-btn';
    submitBtn.textContent = 'Submit';
    submitBtn.type = 'button';
    submitBtn.className = 'bg-green-700 hover:bg-green-600 text-white px-5 py-2 rounded-md font-semibold hidden';
    formEl.after(submitBtn); // place below options

    const loadQuestion = (index) => {
        const q = questions[index];
        document.getElementById('current-question').textContent = index + 1;
        questionEl.textContent = q.question;
        formEl.innerHTML = '';

        Object.entries(q.options).forEach(([key, value]) => {
            const label = document.createElement('label');
            label.className = 'flex items-center space-x-2 cursor-pointer';

            const input = document.createElement('input');
            input.type = 'radio';
            input.name = 'option';
            input.value = key;
            input.className = 'accent-indigo-600';

            const span = document.createElement('span');
            span.textContent = `${key}. ${value}`;

            label.appendChild(input);
            label.appendChild(span);
            formEl.appendChild(label);
        });

        feedbackEl.classList.add('hidden');
        explanationEl.classList.add('hidden');
        submitBtn.classList.add('hidden');
    };

    // ✅ Show Submit button when an option is selected
    formEl.addEventListener('change', () => {
        submitBtn.classList.remove('hidden');
    });

    // ✅ Handle Submit button click
    submitBtn.addEventListener('click', async () => {
        const selected = formEl.querySelector('input[name="option"]:checked');
        if (!selected) return alert('Please select an option.');

        const selectedValue = selected.value;
        const currentQuestion = questions[current];

        // Send answer to backend
        const response = await fetch(`/exam/${examId}/answer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                question_id: currentQuestion.id,
                selected_option: selectedValue
            })
        });

        const data = await response.json();
        const correctAnswer = currentQuestion.correct;
        const explanation = currentQuestion.explanation || "No explanation provided.";

        feedbackEl.classList.remove('hidden');
        explanationEl.classList.remove('hidden');

        // ✅ Check and show feedback
        if (data.is_correct) {
            feedbackEl.textContent = '✅ Correct!';
            feedbackEl.className = 'mt-4 p-3 rounded-md border border-green-300 bg-green-100 text-green-700 font-semibold';
        } else {
            feedbackEl.textContent = `❌ Incorrect! The correct answer is: ${correctAnswer}. ${currentQuestion.options[correctAnswer]}`;
            feedbackEl.className = 'mt-4 p-3 rounded-md border border-red-300 bg-red-100 text-red-700 font-semibold';

            // Highlight correct answer
            const correctOption = formEl.querySelector(`input[value="${correctAnswer}"]`);
            if (correctOption) {
                correctOption.parentElement.classList.add('bg-green-100', 'border', 'border-green-400', 'rounded');
            }
        }

        // ✅ Show explanation
        explanationEl.textContent = `💡 Explanation: ${explanation}`;

        // Disable all options and hide the submit button
        formEl.querySelectorAll('input[name="option"]').forEach(input => input.disabled = true);
        submitBtn.classList.add('hidden');
    });

    // ✅ Handle next question
    nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (current < total - 1) {
            current++;
            loadQuestion(current);
        } else {
            alert('Exam finished!');
            window.location.href = "{{ route('done') }}";
        }
    });

    // ✅ Countdown timer
    let totalSeconds = {{ $remainingSeconds }};
    const timer = setInterval(() => {
        totalSeconds--;
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        document.getElementById('hours').textContent = hours;
        document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');

        if (totalSeconds <= 0) {
            clearInterval(timer);
            alert('Time is up!');
        }
    }, 1000);

    // Load the first question
    loadQuestion(current);
});
</script>
@endsection
