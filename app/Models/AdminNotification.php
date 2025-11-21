<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdminNotification extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'admin_notifications';
    protected $fillable = [
        'user_id', 'exam_id', 'type', 'is_read'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function exam() { return $this->belongsTo(Exam::class); }

    public function getMessageAttribute()
    {
        $username = $this->user->name;
        $examName = $this->exam->title;

        return match ($this->type) {
            'passed' => "$username have completed and passed the $examName examination. Click to find out more.",
            'failed_attempts' => "$username have completed and failed the $examName examination, all 3 attempts of the examination have been used. Click to find out more.",
            'failed_deadline' => "$username failed the $examName examination, the user did not complete the examination before the dateline. Click to find out more.",
        };
    }

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

}

