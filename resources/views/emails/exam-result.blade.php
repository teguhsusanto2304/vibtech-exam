<x-mail::message>
# Hello {{ $student->name }},

Please view your examination result below.

- **Candidate Name:** {{ $student->name }}
- **Candidate Company:** {{ $student->company }}
- **Examination Name:** {{ $result->exam->title }}
- **Examination Date Completion:** {{ $result->finished_at->format("d M Y h:i:s") }}
- **Examination Attempts:** {{ $result->attempts_used }}
- **Your Grade:** {{ $result->scores }} %
- **Passing Grade:** {{ $result->exam->pass_mark }}%
@if($status === 'cancel')
- **Result:** **FAILED**
@else
- **Result:** **PASSED**
@endif

@if($status === 'passed')
- **Remarks:** 🎉 You passed the examination. 
@else
- **Remarks:** ❌ You failed the examination.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
