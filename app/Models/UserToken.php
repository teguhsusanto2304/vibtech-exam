<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'user_agent',
        'ip_address',
        'expires_at',
    ];

    public static function generateToken()
    {
        return hash('sha256', Str::random(60));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
