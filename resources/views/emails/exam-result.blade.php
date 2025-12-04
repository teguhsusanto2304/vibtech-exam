<x-mail::message>
# Hello {{ $student->name }},

Your exam results are ready.

- **Exam:** {{ $result->exam->title }}
- **Score:** {{ $result->scores }}
- **Passing Mark:** {{ $result->exam->pass_mark }}
- **Status:** **{{ strtoupper($status) }}**

@if($status === 'passed')
🎉 Congratulations! You passed the exam.
@else
❌ Unfortunately, you did not meet the passing score. Please try again.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
