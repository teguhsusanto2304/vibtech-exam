<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class UserExam extends Model
{
    const STATUSES = [
        'cancel' => 'Cancel',
        'pending' => 'Pending',
        //'started' => 'Started',
        //'completed' => 'Completed',
    ];
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'user_exams';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'exam_id',
        'data_status',
        'started_at',
        'finished_at',
        'scores',
        'durations',
        'active_date',
        'end_date',
        'attempts_used'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'active_date' => 'datetime',
        'end_date' => 'datetime',
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

    // Each record belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each record belongs to one Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(UserAnswer::class, 'user_exam_id');
    }

    /**
     * Accessor for badge-style status display
     */
    public function getDataStatusBadgeAttribute()
    {
        return match ($this->data_status) {
            'pending' => '<span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>',
            'started' => '<span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Started</span>',
            'completed' => '<span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Completed</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Unknown</span>',
        };
    }

    public function getDataScoreAttribute()
    {
        return round(($this->answers()->where('is_correct', true)->count()/$this->answers()->count())*100);
    }

    public function getCalculateDurationAttribute()
    {
        // Query the related answers to find the min and max created_at timestamps
        $times = $this->answers()
                    ->selectRaw('MIN(created_at) as start_time, MAX(created_at) as end_time')
                    ->first();

        // Check if any answers exist
        if (!$times || !$times->start_time || !$times->end_time) {
            return null; // Return null if no answers were submitted
        }

        // Convert the database timestamps to Carbon instances
        $startTime = Carbon::parse($times->start_time);
        $endTime = Carbon::parse($times->end_time);

        // Calculate the difference and return it as a CarbonInterval
        return $startTime->diffAsCarbonInterval($endTime);
    }
}
