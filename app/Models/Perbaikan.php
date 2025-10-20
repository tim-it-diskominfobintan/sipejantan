<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $table = "perbaikan";
    protected $primaryKey = 'id_perbaikan';
    protected $guarded = [];
    public $timestamps = true;

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id_laporan');
    }

    public function dokPerbaikan()
    {
        return $this->hasMany(DokPerbaikan::class, 'perbaikan_id', 'id_perbaikan');
    }

    public function transBarangPerbaikan()
    {
        return $this->hasMany(TransPerbaikanBarang::class, 'perbaikan_id', 'id_perbaikan');
    }
}
