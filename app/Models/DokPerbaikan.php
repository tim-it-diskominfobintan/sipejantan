<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokPerbaikan extends Model
{
    use HasFactory;
    protected $table = "dokumen_perbaikan";
    protected $primaryKey = 'id_dok_perbaikan';
    protected $guarded = [];
    public $timestamps = true;

    protected $appends = [
        'foto_perbaikan_url',
    ];

    public function getFotoPerbaikanUrlAttribute()
    {
        return $this->file_perbaikan != null ||  $this->file_perbaikan != '' ? asset('storage/' . $this->file_perbaikan) : asset('assets/global/img/kardus.png');
    }

    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id', 'id_perbaikan');
    }
}
