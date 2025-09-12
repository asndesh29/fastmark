<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        'vehicle_id',
        'provider',
        'policy_number',
        'issue_date',
        'expiry_date',
        'amount',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'amount' => 'decimal'
    ];

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }
}
