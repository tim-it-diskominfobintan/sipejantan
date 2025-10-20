<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class AuthProvider extends Model
{
    use HasFactory;

    protected $table = "auth_providers";
    protected $primaryKey = 'id';
    protected $guarded = [];
    // protected $appends = ['logo_url', 'logo_dark_url'];
    // protected $hidden = ['logo', 'logo_dark'];
    protected $casts = [
        'config' => 'array',
        'is_enabled' => 'boolean',
    ];


}
