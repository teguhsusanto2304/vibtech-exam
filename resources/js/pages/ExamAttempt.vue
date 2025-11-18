<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">{{ exam.title }}</h2>
        <div class="text-right">
          <p class="text-sm text-gray-500">Time Remaining:</p>
          <p class="text-2xl font-bold text-red-600">{{ formattedTime }}</p>
        </div>
      </div>

      <div v-if="questions.length">
        <div v-for="(q, index) in questions" :key="q.id" class="mb-6 border-b pb-4">
          <p class="font-medium mb-2">{{ index + 1 }}. {{ q.text }}</p>
          <div v-for="opt in ['A', 'B', 'C', 'D']" :key="opt" class="ml-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input
                type="radio"
                :name="'q' + q.id"
                :value="opt"
                v-model="answers[q.id]"
                class="text-blue-600 focus:ring-blue-500"
              />
              <span>Option {{ opt }}</span>
            </label>
          </div>
        </div>

        <div class="flex justify-end mt-6">
          <button
            @click="submitExam"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition"
          >
            Submit Exam
          </button>
        </div>
      </div>

      <div v-else class="text-center text-gray-500">Loading questions...</div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const exam = ref({});
const questions = ref([]);
const answers = ref({});
const timeRemaining = ref(0);
const timerInterval = ref(null);

// Restore timer from localStorage (in case of refresh)
const savedTime = localStorage.getItem(`exam_${route.params.id}_remaining`);
if (savedTime) timeRemaining.value = parseInt(savedTime);

// Format time into mm:ss
const formattedTime = computed(() => {
  const minutes = Math.floor(timeRemaining.value / 60);
  const seconds = timeRemaining.value % 60;
  return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
});

onMounted(async () => {
  // Prevent leaving or refreshing
  window.addEventListener('beforeunload', preventUnload);
  window.addEventListener('popstate', preventBack);
  document.addEventListener('visibilitychange', handleVisibility);

  // Fetch exam data
  const examRes = await axios.get(`/exams/${route.params.id}`);
  exam.value = examRes.data;

  const qRes = await axios.get(`/exams/${route.params.id}/questions`);
  questions.value = qRes.data;

  if (!timeRemaining.value) {
    // Convert duration (e.g., minutes → seconds)
    timeRemaining.value = (exam.value.duration ?? 10) * 60;
  }

  startTimer();
});

onBeforeUnmount(() => {
  window.removeEventListener('beforeunload', preventUnload);
  window.removeEventListener('popstate', preventBack);
  document.removeEventListener('visibilitychange', handleVisibility);
  clearInterval(timerInterval.value);
});

function startTimer() {
  timerInterval.value = setInterval(() => {
    if (timeRemaining.value > 0) {
      timeRemaining.value--;
      localStorage.setItem(`exam_${route.params.id}_remaining`, timeRemaining.value);
    } else {
      clearInterval(timerInterval.value);
      autoSubmit();
    }
  }, 1000);
}

function preventUnload(e) {
  e.preventDefault();
  e.returnValue = 'Are you sure you want to leave? Your answers will be lost.';
}

function preventBack() {
  history.pushState(null, null, document.URL);
}

function handleVisibility() {
  if (document.hidden) {
    alert('Tab switching is not allowed during the exam.');
  }
}

async function submitExam() {
  clearInterval(timerInterval.value);
  localStorage.removeItem(`exam_${route.params.id}_remaining`);
  await axios.post(`/exams/${route.params.id}/answers`, { answers: answers.value });
  router.push(`/result/${route.params.id}`);
}

function autoSubmit() {
  alert('Time is up! Submitting your exam automatically.');
  submitExam();
}
</script>
