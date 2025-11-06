<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Vibtech Genesis Examination Portal</title>

  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      -webkit-text-size-adjust: 100%;
    }

    :root {
      --color-primary: #0A2342;
    }

    body {
      margin: 0;
      padding: 0;
      width: 100%;
      min-height: 100vh;
      overflow-x: hidden;
    }

    .peer:checked ~ .peer-checked\:bg-primary {
      background-color: var(--color-primary) !important;
    }

    .peer:checked ~ .peer-checked\:border-primary {
      border-color: var(--color-primary) !important;
    }

    .peer:checked ~ .peer-checked\:text-white {
      color: white !important;
    }

    /* Fix label wrapping and spacing */
    label.flex {
      align-items: flex-start;
    }

    /* Force proper width inside flexbox layouts */
    .layout-content-container,
    main,
    form {
      width: 100%;
      max-width: 960px;
      min-width: 0;
    }

    /* Edge flex fix for shrinkable content */
    .option-label-content {
      min-width: 0;
      flex: 1 1 auto;
      word-wrap: break-word;
    }

    /* Slightly tighter radiobutton labels */
    input[type="radio"] {
      transform: scale(1.1);
    }
  </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display">
  <div class="flex flex-col min-h-screen w-full">
    <div class="flex flex-1 justify-center py-5">
      <div class="layout-content-container flex flex-col bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden">
        <!-- HEADER -->
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-gray-700 px-6 sm:px-10 py-3 bg-white dark:bg-background-dark">
          <div class="flex items-center gap-4 text-gray-800 dark:text-white">
            <span class="material-symbols-outlined text-primary text-3xl">waves</span>
            <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Vibtech Genesis Examination Portal</h2>
          </div>
        </header>

        <div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-lg mt-10">
    <div class="flex justify-between items-center border-b pb-3 mb-4">
        <h2 class="text-lg font-bold">Q <span id="current-question">1</span> of <span id="total-questions">0</span></h2>
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

    <div id="feedback" class="hidden mt-4 p-3 rounded-md border border-green-300 bg-green-100 text-green-700 font-semibold">
        ✅ Correct!
    </div>

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
    const nextBtn = document.getElementById('next-btn');

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
    };

    formEl.addEventListener('change', async (e) => {
    const selected = e.target.value;
    const currentQuestion = questions[current];
    const correct = currentQuestion.correct;

    // POST answer to backend
    await fetch(`/exam/${examId}/answer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            question_id: currentQuestion.id,
            selected_option: selected
        })
    }).then(res => res.json())
      .then(data => {
          if (data.is_correct) {
              feedbackEl.classList.remove('hidden');
              feedbackEl.textContent = '✅ Correct!';
              feedbackEl.className = 'mt-4 p-3 rounded-md border border-green-300 bg-green-100 text-green-700 font-semibold';
          } else {
              feedbackEl.classList.remove('hidden');
              feedbackEl.textContent = '❌ Incorrect!';
              feedbackEl.className = 'mt-4 p-3 rounded-md border border-red-300 bg-red-100 text-red-700 font-semibold';
          }
      });
});

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

    // Start countdown (example: 5 minutes)
    let totalSeconds = 60 * 5;
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

    // Initialize first question
    loadQuestion(current);
});
</script>
</body>
</html>
