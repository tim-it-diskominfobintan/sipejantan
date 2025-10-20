<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = "m_kecamatan";
    protected $primaryKey = 'id_kecamatan';
    protected $guarded = [];
    public $timestamps = true;

    public function assets()
    {
        return $this->hasMany(Asset::class, 'kecamatan_id', 'id_kecamatan');
    }
}
