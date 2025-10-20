<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokLaporan extends Model
{
    use HasFactory;
    protected $table = "dokumen_laporan";
    protected $primaryKey = 'id_dok_laporan';
    protected $guarded = [];
    public $timestamps = true;

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
}
