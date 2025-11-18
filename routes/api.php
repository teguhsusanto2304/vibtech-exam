<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\AnswerController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/exams', [ExamController::class, 'index']);
    Route::get('/exams/{id}', [ExamController::class, 'show']);
    Route::get('/exams/{id}/questions', [ExamController::class, 'questions']);
    Route::post('/exams/{id}/answers', [ExamController::class, 'submitAnswer']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/exam/detail', [ExamController::class, 'detail']);
    Route::get('/exam/{id}/question', [ExamController::class, 'getQuizQuestion']);
    Route::post('/exam/{exam}/cheat', [AuthController::class, 'logCheat']);
    Route::get('/exam/{userExamId}/result', [ExamController::class, 'examResult']);
});

