<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = "m_barang";
    protected $primaryKey = 'id_barang';
    protected $guarded = [];
    public $timestamps = true;

    protected $appends = [
        'foto_barang_url',
    ];

    public function getFotoBarangUrlAttribute()
    {
        return $this->foto_barang != null ||  $this->foto_barang != '' ? asset('storage/' . $this->foto_barang) : asset('assets/global/img/kardus.png');
    }

    public function detailBarang()
    {
        return $this->hasMany(DetailBarang::class, 'barang_id', 'id_barang');
    }
}
