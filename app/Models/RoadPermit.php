<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoadPermit extends Model
{
    protected $table = 'road_permits';

    protected $fillable = [
        'vehicle_id',
        'permit_number',
        'issue_date',
        'expiry_date',
        'remarks',
        'status'
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date' => 'date',
        'expiry_date' => 'date'
    ];

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }
}
