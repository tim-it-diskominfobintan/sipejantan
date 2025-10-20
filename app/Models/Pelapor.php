<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelapor extends Model
{
    use HasFactory;
    protected $table = "pelapor";
    protected $primaryKey = 'id_pelapor';
    protected $guarded = [];
    public $timestamps = true;

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id_kecamatan');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id_kelurahan');
    }

    // public function laporan()
    // {
    //     return $this->belongsTo(Laporan::class, 'id_pelapor', 'pelapor_id');
    // }

    public function laporan()
    {
        return $this->hasOne(Laporan::class, 'pelapor_id', 'id_pelapor');
    }
}
