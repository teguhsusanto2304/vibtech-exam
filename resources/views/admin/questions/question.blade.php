@extends('layouts.admin.app')

@section('title', 'User Management')

@section('content')
<h1 class="text-text-light dark:text-text-dark text-3xl font-bold leading-tight tracking-tight">{{ $examTitle }}</h1>
<form action="{{ route('admin.exams.store') }}" method="post">

    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<!-- Left Panel: Available Questions -->
<div class="flex flex-col bg-content-light dark:bg-content-dark rounded-xl border border-border-light dark:border-border-dark">
<div class="p-4 border-b border-border-light dark:border-border-dark">
<h2 class="text-text-light dark:text-text-dark text-lg font-bold">Question Bank</h2>
<p class="text-subtle-light dark:text-subtle-dark text-sm">Select questions to add to the exam.</p>
</div>
<!-- Search and Filters -->
<div class="p-4 flex flex-col sm:flex-row gap-3 border-b border-border-light dark:border-border-dark">
<!-- SearchBar -->
<div class="flex-grow">
<label class="flex flex-col min-w-40 h-10 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full">
<div class="text-subtle-light dark:text-subtle-dark flex border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark items-center justify-center pl-3 rounded-l-lg border-r-0">
<span class="material-symbols-outlined text-lg">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-content-light dark:focus:ring-offset-content-dark border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark h-full placeholder:text-subtle-light dark:placeholder:text-subtle-dark px-2 rounded-l-none border-l-0 text-sm font-normal" placeholder="Search questions..."/>
</div>
</label>
</div>
<select class="form-select flex-shrink-0 w-full sm:w-32 h-10 rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-sm focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-content-light dark:focus:ring-offset-content-dark">
<option>By Topic</option>
<option>Vibration Analysis</option>
<option>Thermography</option>
</select>
<select class="form-select flex-shrink-0 w-full sm:w-32 h-10 rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-sm focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-content-light dark:focus:ring-offset-content-dark">
<option>By Difficulty</option>
<option>Easy</option>
<option>Medium</option>
<option>Hard</option>
</select>
</div>
<!-- Question List -->
<div class="p-2 space-y-2 overflow-y-auto" style="max-height: 500px;">
<div class="flex items-center justify-between p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark">
<div class="flex items-center gap-3">
<input class="form-checkbox rounded text-primary focus:ring-primary" type="checkbox"/>
<p class="text-sm">What is the primary cause of imbalance in rotating machinery?</p>
</div>
<button class="p-1 rounded-full text-subtle-light dark:text-subtle-dark hover:bg-primary/20 hover:text-primary transition-colors">
<span class="material-symbols-outlined text-lg">add_circle</span>
</button>
</div>
<div class="flex items-center justify-between p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark">
<div class="flex items-center gap-3">
<input class="form-checkbox rounded text-primary focus:ring-primary" type="checkbox"/>
<p class="text-sm">Which sensor is most commonly used for vibration analysis?</p>
</div>
<button class="p-1 rounded-full text-subtle-light dark:text-subtle-dark hover:bg-primary/20 hover:text-primary transition-colors">
<span class="material-symbols-outlined text-lg">add_circle</span>
</button>
</div>
<div class="flex items-center justify-between p-3 rounded-lg bg-green-500/10">
<div class="flex items-center gap-3">
<input checked="" class="form-checkbox rounded text-primary focus:ring-primary" disabled="" type="checkbox"/>
<p class="text-sm text-success">How does temperature affect bearing life?</p>
</div>
<button class="p-1 rounded-full text-success" disabled="">
<span class="material-symbols-outlined text-lg">check_circle</span>
</button>
</div>
<div class="flex items-center justify-between p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark">
<div class="flex items-center gap-3">
<input class="form-checkbox rounded text-primary focus:ring-primary" type="checkbox"/>
<p class="text-sm">Define the term 'resonance' in mechanical systems.</p>
</div>
<button class="p-1 rounded-full text-subtle-light dark:text-subtle-dark hover:bg-primary/20 hover:text-primary transition-colors">
<span class="material-symbols-outlined text-lg">add_circle</span>
</button>
</div>
<div class="flex items-center justify-between p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark">
<div class="flex items-center gap-3">
<input class="form-checkbox rounded text-primary focus:ring-primary" type="checkbox"/>
<p class="text-sm">What is the purpose of a Fast Fourier Transform (FFT) in diagnostics?</p>
</div>
<button class="p-1 rounded-full text-subtle-light dark:text-subtle-dark hover:bg-primary/20 hover:text-primary transition-colors">
<span class="material-symbols-outlined text-lg">add_circle</span>
</button>
</div>
</div>
<!-- Bulk Action Footer -->
<div class="p-4 border-t border-border-light dark:border-border-dark mt-auto">
<button class="w-full text-center px-4 py-2 text-sm font-semibold text-white rounded-lg bg-primary hover:bg-primary/90 transition-colors disabled:bg-gray-300 dark:disabled:bg-gray-600">Add Selected (3)</button>
</div>
</div>
<!-- Right Panel: Assigned Questions -->
<div class="flex flex-col bg-content-light dark:bg-content-dark rounded-xl border border-border-light dark:border-border-dark">
<div class="p-4 border-b border-border-light dark:border-border-dark flex justify-between items-center">
<div>
<h2 class="text-text-light dark:text-text-dark text-lg font-bold">Questions in Exam</h2>
<p class="text-subtle-light dark:text-subtle-dark text-sm">Currently 1 of 100 questions assigned.</p>
</div>
<button class="text-danger text-sm font-semibold hover:underline">Clear All</button>
</div>
<!-- Empty State Example -->
<!-- <div class="p-4 flex-grow flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-6xl text-subtle-light/50 dark:text-subtle-dark/50">quiz</span>
                        <h3 class="font-bold mt-4">This exam has no questions</h3>
                        <p class="text-sm text-subtle-light dark:text-subtle-dark max-w-xs mt-1">Add questions from the Question Bank on the left to get started building your exam.</p>
                    </div> -->
<!-- Assigned Question List -->
<div class="p-2 space-y-2 overflow-y-auto" style="max-height: 500px;">
<div class="flex items-center justify-between p-3 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark">
<div class="flex items-center gap-3">
<button class="cursor-grab text-subtle-light dark:text-subtle-dark">
<span class="material-symbols-outlined">drag_indicator</span>
</button>
<span class="font-semibold text-sm w-6">1.</span>
<p class="text-sm">How does temperature affect bearing life?</p>
</div>
<button class="p-1 rounded-full text-subtle-light dark:text-subtle-dark hover:bg-danger/20 hover:text-danger transition-colors">
<span class="material-symbols-outlined text-lg">remove_circle</span>
</button>
</div>
<!-- More assigned questions would go here -->
</div>
<div class="p-4 border-t border-border-light dark:border-border-dark mt-auto">
<p class="text-xs text-center text-subtle-light dark:text-subtle-dark">Drag and drop questions to reorder them.</p>
</div>
</div>
</div>
</form>
@endsection
