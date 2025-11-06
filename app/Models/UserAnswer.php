<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserAnswer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'user_answers';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_exam_id',
        'exam_question_id',
        'user_option',
        'is_correct',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Boot method for model event hooks.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationships
     */

    // Each answer belongs to one user_exam (the specific attempt)
    public function userExam()
    {
        return $this->belongsTo(UserExam::class, 'user_exam_id');
    }

    // Each answer belongs to a specific exam question
    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class, 'exam_question_id');
    }

    /**
     * Accessor: formatted badge for correctness
     */
    public function getIsCorrectBadgeAttribute()
    {
        return $this->is_correct
            ? '<span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Correct</span>'
            : '<span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Wrong</span>';
    }
}
