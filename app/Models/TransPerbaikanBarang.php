<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransPerbaikanBarang extends Model
{
    use HasFactory;

    protected $table = "trans_barang_perbaikan";
    protected $primaryKey = 'id_trans_barang_perbaikan';
    protected $guarded = [];
    public $timestamps = true;

    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id', 'id_perbaikan');
    }

    public function detailbarang()
    {
        return $this->belongsTo(DetailBarang::class, 'detail_barang_id', 'id_detail_barang');
    }
}
