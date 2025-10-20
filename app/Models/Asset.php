<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $table = "asset";
    protected $primaryKey = 'id_asset';
    protected $guarded = [];
    public $timestamps = true;

    public function penanggung_jawab()
    {
        return $this->belongsTo(PenanggungJawab::class, 'penanggung_jawab_id', 'id_penanggung_jawab');
    }

    public function jenis_asset()
    {
        return $this->belongsTo(JenisAsset::class, 'jenis_asset_id', 'id_jenis_asset');
    }

    public function jalan()
    {
        return $this->belongsTo(Jalan::class, 'jalan_id', 'id_jalan');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'asset_id', 'id_asset');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id_kelurahan');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id_kecamatan');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenAsset::class, 'asset_id');
    }

    public function latestLaporan()
    {
        // latestOfMany menggunakan created_at/primary key untuk memilih "paling terakhir"
        return $this->hasOne(Laporan::class, 'asset_id', 'id_asset')->latestOfMany('id_laporan');
    }
}
