<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenanggungJawab extends Model
{
    use HasFactory;

    protected $table = "m_penanggung_jawab";
    protected $primaryKey = 'id_penanggung_jawab';
    protected $guarded = [];
    public $timestamps = true;
}
