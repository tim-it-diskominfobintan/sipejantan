<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenAsset extends Model
{
    use HasFactory;
    protected $table = "dokumen_asset";
    protected $primaryKey = 'id_dok_asset';
    protected $guarded = [];
    public $timestamps = true;

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
