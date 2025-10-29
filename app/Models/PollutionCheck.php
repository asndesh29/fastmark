<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollutionCheck extends Model
{
    protected $table = 'pollution_checks';

    protected $fillable = [
        'vehicle_id',
        'certificate_number',
        'check_date',
        'issue_date',
        'expiry_date',
        'remarks',
        'status'
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'check_date' => 'string',
        'issue_date' => 'string',
        'expiry_date' => 'string'
    ];

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }
}
