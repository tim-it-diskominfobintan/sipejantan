<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarang extends Model
{
    use HasFactory;

    protected $table = "detail_barang";
    protected $primaryKey = 'id_detail_barang';
    protected $guarded = [];
    public $timestamps = true;

    protected $appends = [
        'foto_detail_barang_url',
    ];

    public function getFotoDetailBarangUrlAttribute()
    {
        return $this->foto_detail_barang != null ||  $this->foto_detail_barang != '' ? asset('storage/' . $this->foto_detail_barang) : asset('assets/global/img/kardus.png');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }
}
