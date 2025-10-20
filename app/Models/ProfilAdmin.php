<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilAdmin extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = "profil_admin";
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $appends = [];
    protected $hidden = [];
    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(ProfilAdmin::class, 'user_id', 'id');
    }
}
