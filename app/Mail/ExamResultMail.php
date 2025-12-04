<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExamResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $result;
    public $status;

    public function __construct($student, $result, $status)
    {
        $this->student = $student;
        $this->result = $result;
        $this->status = $status;
    }

    public function build()
    {
        $subject = "Vibtech Examination :: Your Exam Result: " . strtoupper($this->status);

        return $this->subject($subject)
            ->markdown('emails.exam-result');
    }
}
