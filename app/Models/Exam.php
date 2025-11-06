<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Needed for generating UUIDs if you use the creating method

class Exam extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false; // <-- REQUIRED

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string'; // <-- REQUIRED

    // 2. Table Name (Optional but good practice)
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exams';

    // 3. Mass Assignment Protection
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'questions',
        'duration',
        'data_status', // Matches the 'data_status' column in the migration
        'last_modified',
        'instruction',
        'pass_mark',
        'randomize_questions',
        'randomize_options'
    ];

    // 4. Type Casting
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_modified' => 'datetime',
        // Optional: 'questions' => 'integer', 'duration' => 'integer'
    ];
    
    // 5. Automatic UUID Generation (Recommended)

    /**
     * The "booting" method of the model.
     *
     * This is used to automatically generate a UUID before creating a record.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Assign a new UUID to the primary key
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function examQuestions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions', 'exam_id', 'question_id')
                    ->withTimestamps()
                    ->withPivot('order');
    }

    public function masterExamQuestions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function getDataStatusBadgeAttribute()
    {
        return match ($this->data_status) {
            'publish' => '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Publish</span>',
            'draft' => '<span class="inline-flex items-center rounded-full bg-gray-200 px-2.5 py-0.5 text-xs font-medium text-gray-700">Draft</span>',
            'pending' => '<span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pending</span>',
            default => '<span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">' . ucfirst($this->data_status ?? 'Unknown') . '</span>',
        };
    }
}