<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $table = 'exam_questions';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['exam_id', 'question_id', 'order'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Only assign if not already set
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
