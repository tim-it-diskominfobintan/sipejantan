<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPegawai extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = "profil_pegawai";
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $appends = [];
    protected $hidden = [];
    protected $casts = [];
}
