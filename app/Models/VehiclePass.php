<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiclePass extends Model
{
    protected $table = 'vehicle_passes';
    protected $fillable = [
        'vehicle_id',
        'issue_date',
        'expiry_date',
        'inspection_result',
        // 'inspection_date',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date' => 'string',
        // 'inspection_date' => 'string',
        'expiry_date' => 'string',
    ];

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }
}
