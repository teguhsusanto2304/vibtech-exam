<template>
  <div class="p-8">
    <h2 class="text-2xl font-bold mb-4">Available Exams</h2>
    <ul class="space-y-3">
      <li v-for="exam in exams" :key="exam.id" class="border p-4 rounded-lg flex justify-between">
        <div>
          <h3 class="font-semibold">{{ exam.title }}</h3>
          <p class="text-sm text-gray-500">Duration: {{ exam.duration }} minutes</p>
        </div>
        <router-link :to="`/exam/${exam.id}`" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Start
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

const exams = ref([]);

onMounted(async () => {
  const res = await axios.get('/exams');
  exams.value = res.data;
});
</script>
