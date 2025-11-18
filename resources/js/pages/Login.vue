<template>
  <div class="h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg p-8 rounded-lg w-96">
      <h1 class="text-2xl font-bold mb-4 text-center">Student Login</h1>
      <form @submit.prevent="login">
        <input v-model="email" type="email" placeholder="Email" class="w-full border rounded p-2 mb-3" />
        <input v-model="password" type="password" placeholder="Password" class="w-full border rounded p-2 mb-4" />
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
      </form>
      <p v-if="error" class="text-red-500 mt-3 text-center">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const error = ref('');
const router = useRouter();

const login = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie');
    await axios.post('/login', { email: email.value, password: password.value });
    router.push('/exams');
  } catch (e) {
    error.value = 'Invalid credentials.';
  }
};
</script>
