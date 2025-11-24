<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class ExamStatusUpdated extends Notification 
//implements ShouldQueue
{
    //use Queueable;

    protected $resultExam;
    protected $studentName;
    protected $status;

    public function __construct($resultExam,$studentName, $status)
    {
        $this->resultExam = $resultExam;
        $this->studentName = $studentName;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database']; // add 'mail' if needed
    }

    public function toDatabase($notifiable)
    {
        if($this->status==='passed'){
            $msg = "<strong>".$this->studentName."</strong> have <strong class='text-blue-600'>completed</strong> and <strong class='text-green-600'>passed</strong> the <strong>".$this->resultExam->exam->title."</strong> examination.";
        } else {
            if($this->resultExam->attempts_used === 3){
                $msg = "<strong>".$this->studentName."</strong>  have <strong class='text-blue-600'>completed</strong> and <strong class='text-red-600'>failed</strong> the <strong>".$this->resultExam->exam->title."</strong> examination, all 3 attempts of the examination have been used.";
            } else {
                $msg = "<strong>".$this->studentName."</strong>  have <strong class='text-red-600'>failed</strong> the <strong>".$this->resultExam->exam->title."</strong>  examination, the user did not complete the examination before the dateline.";
            }
        }
        return [
            'title' => 'Exam status changed',
            'message' => strip_tags( $msg,'<strong>'),
            'exam_id' => $this->resultExam->id,
            'status' => $this->status,
            'url' => route('admin.users.show', $this->resultExam->user_id),
        ];
    }
}
