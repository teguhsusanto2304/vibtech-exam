<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\SettingsController;

// Public Settings API (No Authentication Required)
Route::prefix('settings')->group(function () {
    Route::get('/branding', [SettingsController::class, 'getBranding'])->name('api.settings.branding');
    Route::get('/logo', [SettingsController::class, 'getLogo'])->name('api.settings.logo');
    Route::get('/app-name', [SettingsController::class, 'getAppName'])->name('api.settings.app-name');
});

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
    
    // Authenticated Settings API
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'getSettings'])->name('api.settings.all');
        Route::get('/{key}', [SettingsController::class, 'getSetting'])->name('api.settings.single');
    });
});

