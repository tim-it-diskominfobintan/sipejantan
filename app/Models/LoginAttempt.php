<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $table = 'login_attempts';
    protected $fillable = [
        'user_id',
        'login_at',
        'logout_at',
        'ip_address',
        'user_agent',
        'platform',
        'browser',
        'location',
        'auth_provider',
        'status',
    ];
    protected $dates = [
        'login_at', 
        'logout_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}