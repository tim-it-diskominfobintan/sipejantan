<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisAsset extends Model
{
    use HasFactory;
    protected $table = "m_jenis_asset";
    protected $primaryKey = 'id_jenis_asset';
    protected $guarded = [];
    public $timestamps = true;
}
