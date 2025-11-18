<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'name',
        'company',
        'data_status',
        'attempts_used',
        'last_score',
        'last_outcome',
        'last_attempt_date',
        'password',
        'role'
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate UUID on create
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function userExams()
    {
        return $this->hasMany(UserExam::class);
    }

    public function tokensxxx()
    {
        return $this->hasMany(UserToken::class);
    }


    protected $keyType = 'string';
    public $incrementing = false;
}
