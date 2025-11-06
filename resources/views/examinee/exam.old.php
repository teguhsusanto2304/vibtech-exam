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

        <!-- MAIN CONTENT -->
        <form id="question-form" method="POST" action="{{ route('exam.answer', $question->id) }}" class="flex flex-col w-full">
          @csrf

          <main class="bg-white dark:bg-background-dark p-6 sm:p-10 w-full">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 w-full">
              <h1 class="text-gray-800 dark:text-white text-2xl sm:text-3xl font-bold leading-tight">Q 12 of 300</h1>
              <div class="flex flex-col items-end">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Time Remaining</p>
                <div class="flex gap-2 sm:gap-4">
                  
                      <div class="flex flex-col items-center gap-2">
                          <div class="flex h-14 w-16 sm:w-20 items-center justify-center rounded-lg px-3 bg-background-light dark:bg-gray-800">
                              <p class="text-gray-800 dark:text-white text-xl sm:text-2xl font-bold leading-tight" id="hours"></p>
                          </div>
                          <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm font-normal leading-normal">Hours</p>
                      </div>
                      <div class="flex flex-col items-center gap-2">
                          <div class="flex h-14 w-16 sm:w-20 items-center justify-center rounded-lg px-3 bg-background-light dark:bg-gray-800">
                              <p class="text-gray-800 dark:text-white text-xl sm:text-2xl font-bold leading-tight" id="minutes"></p>
                          </div>
                          <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm font-normal leading-normal">Minutes</p>
                      </div>
                      <div class="flex flex-col items-center gap-2">
                          <div class="flex h-14 w-16 sm:w-20 items-center justify-center rounded-lg px-3 bg-background-light dark:bg-gray-800">
                              <p class="text-gray-800 dark:text-white text-xl sm:text-2xl font-bold leading-tight" id="seconds"></p>
                          </div>
                          <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm font-normal leading-normal">Seconds</p>
                      </div>
                      <script>
        // *** 1. Set the Target Date ***
        const countDownDate = new Date("Nov 11, 2025 10:00:00").getTime();

        // *** 2. Update the Countdown Every Second ***
        const x = setInterval(function() {

            // *** 3. Calculate the Time Difference ***
            const now = new Date().getTime();
            const distance = countDownDate - now;

            // *** 4. Convert Time to Units ***
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // *** 5. Display the Result ***
            //document.getElementById("timer").innerHTML = days + "d " + hours + "h "
            //+ minutes + "m " + seconds + "s ";
            document.getElementById("hours").innerHTML = hours;
            document.getElementById("minutes").innerHTML = minutes;
            document.getElementById("seconds").innerHTML = seconds;

            // *** 6. Handle the Countdown End ***
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("timer").innerHTML = "COUNTDOWN FINISHED!";
            }
        }, 1000);
    </script>

                </div>
              </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-8 w-full">
              <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-white break-words">{{ $question->question_stem }}</h2>

              @php
                  $arrKeyChar = ['A','B','C','D'];
                  $correctKey = strtolower($question->correct_option);
                  $correctOptionColumn = 'option_' . $correctKey;
                  $correctOptionText = $question->{$correctOptionColumn};
              @endphp

              <div class="space-y-4 w-full">
                @foreach($shuffledOptions as $key => $option)
                <label class="flex items-start gap-3 cursor-pointer w-full">
                  <input type="radio" name="answer" value="{{ $option }}" class="text-blue-600 mt-1">
                  <span class="option-label-content text-gray-800 dark:text-gray-200 text-base">{{ $arrKeyChar[$key] }}. {{ $option }}</span>
                </label>
                @endforeach
              </div>

              <div class="mt-8 flex justify-end w-full">
                <button id="submitBtn"
                        class="bg-[#0A2342] hover:bg-[#081e33] text-white font-bold py-3 px-8 rounded-lg 
                               disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                        disabled
                        type="submit">
                  Submit Answer
                </button>
              </div>
            </div>

            <div class="mt-6 p-4 rounded-lg bg-green-100 border border-green-500 text-green-700 hidden" id="feedback-correct">
              <p class="font-bold">✅ Correct!</p>
            </div>
            <div class="mt-6 p-4 rounded-lg bg-red-100 border border-red-500 text-red-700 hidden" id="feedback-incorrect">
              <p class="font-bold">❌ Incorrect. The correct answer is: {{ $correctOptionText }}</p>
            </div>

            <div class="mt-8 flex justify-end hidden" id="next-button-container">
              <button id="nextBtn" class="bg-[#0A2342] text-white font-bold py-3 px-8 rounded-lg" type="button">Next Question</button>
            </div>
          </main>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('question-form');
      const submitButton = document.getElementById('submitBtn');
      const radios = form.querySelectorAll('input[type="radio"]');

      const checkSelection = () => {
        const anyRadioChecked = Array.from(radios).some(r => r.checked);
        submitButton.disabled = !anyRadioChecked;
      };

      checkSelection();
      form.addEventListener('change', checkSelection);

      submitButton.addEventListener('click', (e) => {
        e.preventDefault();
        const selected = Array.from(radios).find(r => r.checked);
        const isCorrect = selected && selected.value === '{{ $correctOptionText }}';

        submitButton.style.display = 'none';
        document.getElementById(isCorrect ? 'feedback-correct' : 'feedback-incorrect').classList.remove('hidden');
        document.getElementById('next-button-container').classList.remove('hidden');
      });

      document.getElementById('nextBtn').addEventListener('click', () => {
        window.location.href = "{{ route('exam.answer') }}";
      });
    });
  </script>
</body>
</html>
