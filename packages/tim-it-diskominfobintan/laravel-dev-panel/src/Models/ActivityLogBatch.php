<?php

namespace TimItDiskominfoBintan\DevPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogBatch extends Model
{
    use HasFactory;

    protected $table = "activity_log_batches";
    protected $primaryKey = 'id';
    protected $guarded = [];

    protected $casts = [
        'latest_log_at' => 'datetime',
    ];

    public function activities()
    {
        return $this->hasMany(\Spatie\Activitylog\Models\Activity::class, 'batch_uuid', 'batch_uuid');
    }
}
