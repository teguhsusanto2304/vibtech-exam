import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/Login.vue';
import ExamList from './pages/ExamList.vue';
import ExamAttempt from './pages/ExamAttempt.vue';
import ExamResult from './pages/ExamResult.vue';

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: Login },
    { path: '/exams', component: ExamList },
    { path: '/exam/:id', component: ExamAttempt, props: true },
    { path: '/result/:id', component: ExamResult, props: true },
  ]
});
