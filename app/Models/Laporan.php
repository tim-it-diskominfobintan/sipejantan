<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = "laporan";
    protected $primaryKey = 'id_laporan';
    protected $guarded = [];
    public $timestamps = true;

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'teknisi_id', 'id_teknisi');
    }

    public function pelapor()
    {
        return $this->belongsTo(Pelapor::class, 'pelapor_id', 'id_pelapor');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id_asset');
    }

    public function dokumen()
    {
        return $this->hasMany(DokLaporan::class, 'laporan_id', 'id_laporan');
    }

    public function perbaikan()
    {
        return $this->hasMany(Perbaikan::class, 'laporan_id', 'id_laporan');
    }
}
