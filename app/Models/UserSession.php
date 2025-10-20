<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    public $timestamps = true;
    protected $table = "user_sessions";
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $casts = [
        'last_activity' => 'datetime',
    ];
}
