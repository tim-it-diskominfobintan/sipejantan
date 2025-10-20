<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    protected $table = "opname_barang";
    protected $primaryKey = 'id_opname';
    protected $guarded = [];
    public $timestamps = true;

    public function detailbarang()
    {
        return $this->belongsTo(DetailBarang::class, 'detail_barang_id', 'id_detail_barang');
    }
}
