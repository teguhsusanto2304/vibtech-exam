<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamineeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CKEditorController;

Route::post('/ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');

Route::get('/', [ExamineeController::class, 'showLoginForm'])->name('login');
Route::post('/login', [ExamineeController::class, 'login'])->name('login.submit');
Route::post('/logout', [ExamineeController::class, 'logout'])->name('logout');

Route::get('/dashboard', [ExamineeController::class,'dashboard'])->name('dashboard');
Route::get('/{examId}/exam', [ExamineeController::class,'exam'])->name('exam');
Route::get('/exam/{examId}/questions', [ExamineeController::class, 'questions'])->name('exam.questions');
Route::post('/exam/{examId}/answer', [ExamineeController::class, 'storeAnswer'])
    ->name('exam.answer.store');
Route::post('/exam/answer', [ExamineeController::class,'exam'])->name('exam.answer');
Route::get('/done', [ExamineeController::class,'done'])->name('done');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminController::class,'login'])->name('admin.login');
    Route::post('/admin/dologin', [AdminController::class,'dologin'])->name('admin.dologin');
});


Route::middleware('auth')->group(function () {

    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/admin/dashboard', [AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [UserController::class,'index'])->name('admin.users');
    Route::get('/admin/users/create', [UserController::class,'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [UserController::class,'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}/update', [UserController::class,'update'])->name('admin.users.update');
    Route::get('/admin/users/{id}/show', [UserController::class,'show'])->name('admin.users.show');
    Route::get('/admin/users/{id}/assign-exam', [UserController::class,'assignExam'])->name('admin.users.assign-exam');
    Route::post('/admin/users/{userId}/save-assign-exam',[UserController::class,'saveAssignExam'])->name('admin.users.save-assign-exam');
    Route::get('/admin/users/{id}/edit', [UserController::class,'edit'])->name('admin.users.edit');
    Route::delete('/admin/users/{id}/destroy', [UserController::class,'destroy'])->name('admin.users.destroy');

    Route::get('/admin/exams', [ExamController::class,'index'])->name('admin.exams');
    Route::get('/admin/exams/create', [ExamController::class,'create'])->name('admin.exams.create');
    Route::post('/admin/exams/store', [ExamController::class,'store'])->name('admin.exams.store');
    Route::put('/admin/exams/{id}/update', [ExamController::class,'update'])->name('admin.exams.update');
    Route::delete('/admin/exams/{id}/destroy', [ExamController::class,'destroy'])->name('admin.exams.destroy');
    Route::get('/admin/exams/{id}/show', [ExamController::class,'show'])->name('admin.exams.show');
    Route::get('/admin/exams/{id}/edit', [ExamController::class,'edit'])->name('admin.exams.edit');
    Route::put('/admin/exams/{id}/update-status',[ExamController::class,'examUpdateStatus'])->name('admin.exams.update-status');
    Route::get('/admin/exams/{id}/questions', [ExamController::class,'questions'])->name('admin.exams.questions');
    Route::post('/admin/exams/{id}/assign-questions', [ExamController::class, 'assignQuestions'])
        ->name('admin.exams.assign-questions');
    Route::delete('/admin/exams/{id}/clear-questions', [ExamController::class, 'clearQuestions'])
        ->name('admin.exams.clear-questions');
    Route::delete('/admin/exams/{examId}/{questionId}/remove-question', [ExamController::class, 'removeQuestion'])
        ->name('admin.exams.remove-question');

    Route::get('/admin/question-banks', [QuestionController::class,'index'])->name('admin.question-banks');
    Route::get('/admin/question-banks/{id}/show', [QuestionController::class,'show'])->name('admin.question-banks.show');
    Route::get('/admin/question-banks/{id}/edit', [QuestionController::class,'edit'])->name('admin.question-banks.edit');
    Route::delete('/admin/question-banks/{id}/destroy', [QuestionController::class,'destroy'])->name('admin.question-banks.destroy');
    Route::get('/admin/question-banks/create', [QuestionController::class,'create'])->name('admin.question-banks.create');
    Route::post('/admin/question-banks/store', [QuestionController::class,'store'])->name('admin.question-banks.store');
    Route::put('/admin/question-banks/{id}/update', [QuestionController::class,'update'])->name('admin.question-banks.update');
    Route::delete('/admin/question-banks/{id}/destroy', [QuestionController::class,'destroy'])->name('admin.question-banks.destroy');
});
