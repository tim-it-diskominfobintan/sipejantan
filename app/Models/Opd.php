<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    use HasFactory;

    protected $table = "opd";
    protected $primaryKey = 'id';

    protected $guarded = [];
    protected $appends = ['logo_url'];
    protected $hidden = ['logo'];

    public function getLogoUrlAttribute()
    {
        return $this->logo != null ? asset('storage/' . $this->logo) : asset('assets/global/img/logo_opd/bintan.png');
    }
}
