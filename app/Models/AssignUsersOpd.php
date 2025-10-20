<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignUsersOpd extends Model
{
    use HasFactory;

    protected $table = 'assign_users_opd';
    protected $guarded = [];

    // scope untuk yang aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function opd()
    {
        return $this->belongsTo(Opd::class);
    }
}
