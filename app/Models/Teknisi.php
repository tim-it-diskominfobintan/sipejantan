<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    use HasFactory;

    protected $table = "m_teknisi";
    protected $primaryKey = 'id_teknisi';
    protected $guarded = [];
    public $timestamps = true;

    public function penanggung_jawab()
    {
        return $this->belongsTo(PenanggungJawab::class, 'penanggung_jawab_id', 'id_penanggung_jawab');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id_kelurahan');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id_kecamatan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_teknisi', 'teknisi_id');
    }
}
