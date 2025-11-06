@extends('layouts.admin.app')

@section('title', 'User Management')

@section('content')
@if ($errors->any())
    <x-alert-message type="error">
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
<x-user-header :user="$user" /> 
<div class="w-full bg-white shadow-xl rounded-2xl flex-1 p-4 ml-1 mr-5 mt-4">

    <form class="space-y-8" action="{{ route('admin.users.save-assign-exam',['userId'=>$user->id]) }}" method="post">
        @csrf
<div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-6">

<!-- Select Exam -->
<label class="flex flex-col">
<p class="text-sm font-medium leading-normal pb-2 text-slate-700 dark:text-slate-300">Select Exam</p>
<select name="exam_id" class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-background-light dark:bg-slate-800 focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-3 text-base font-normal leading-normal">
<option value="">Select a certification exam</option>
@foreach($exams as $exam)
<option value="{{ $exam->id }}">{{ $exam->title }}</option>
@endforeach
</select>
</label>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-6">
<!-- Completion Date & Time -->
 <label class="flex flex-col">
    <p class="text-sm font-medium leading-normal pb-2 text-slate-700 dark:text-slate-300">
        Active Date &amp; Time
    </p>
    <input 
        name="active_date"
        type="datetime-local"
        class="w-full min-w-0 flex-1 rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-background-light dark:bg-slate-800 focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-3 text-base font-normal leading-normal"
    />
</label>


<label class="flex flex-col">
    <p class="text-sm font-medium leading-normal pb-2 text-slate-700 dark:text-slate-300">
        End Date &amp; Time
    </p>
    <input 
        name="end_date"
        type="datetime-local"
        class="w-full min-w-0 flex-1 rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-background-light dark:bg-slate-800 focus:border-primary h-12 placeholder:text-slate-400 dark:placeholder:text-slate-500 px-3 text-base font-normal leading-normal"
    />
</label>

</div>
<!-- Buttons -->
<div class="flex justify-end gap-4 pt-8 border-t border-slate-200 dark:border-slate-800">
<a href="{{ route('admin.users.show',['id'=>$user->id]) }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors" >Cancel</a>
<button class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-primary text-white hover:bg-primary/90 transition-colors" type="submit">Submit Record</button>
</div>
</form>
</div>
@endsection
