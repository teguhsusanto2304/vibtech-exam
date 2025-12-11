<x-mail::message>
{{-- Logo dari Settings Table --}}
@php
    $appLogo = getSetting('app_logo', '/images/logo.png');
    $appName = getSetting('app_name', config('app.name'));
@endphp

<div style="text-align: center; margin-bottom: 30px;">
    @if($appLogo && $appLogo !== '/images/logo.png')
        <img src="{{ $appLogo }}" alt="{{ $appName }} Logo" style="max-width: 200px; height: auto;">
    @else
        <div style="font-size: 24px; font-weight: bold; color: #005A9C;">
            {{ $appName }}
        </div>
    @endif
</div>

# Examination Result Notification

Hello **{{ $student->name }}**,

Thank you for completing the examination. Please review your results below:

---

## Candidate Information
- **Full Name:** {{ $student->name }}
- **Company:** {{ $student->company ?? 'N/A' }}
- **Email:** {{ $student->email }}

## Examination Details
- **Examination Name:** {{ $result->exam->title }}
- **Completion Date:** {{ $result->finished_at->format("d M Y, H:i") }}
- **Total Attempts:** {{ $result->attempts_used }} of 3
- **Your Score:** <strong style="color: {{ $result->scores >= $result->exam->pass_mark ? '#10B981' : '#EF4444' }};">{{ $result->scores }}%</strong>
- **Passing Score:** {{ $result->exam->pass_mark }}%

## Result Status
@if($status === 'passed')
<div style="background-color: #D1FAE5; border-left: 4px solid #10B981; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <span style="color: #065F46; font-weight: bold; font-size: 16px;">✅ PASSED</span><br>
    <span style="color: #047857;">Congratulations! You have successfully passed the examination.</span>
</div>
@else
<div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <span style="color: #7F1D1D; font-weight: bold; font-size: 16px;">❌ FAILED</span><br>
    <span style="color: #991B1B;">You did not pass this examination. You can attempt again if retries are available.</span>
</div>
@endif

---

Best regards,<br>
**{{ $appName }}**<br>
<span style="color: #6B7280; font-size: 12px;">{{ getSetting('support_email', config('mail.from.address')) }}</span>
</x-mail::message>
