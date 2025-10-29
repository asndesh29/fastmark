<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        'vehicle_id',
        'provider_id',
        'policy_number',
        'issue_date',
        'expiry_date',
        'amount',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date' => 'string',
        'expiry_date' => 'string',
        'amount' => 'decimal'
    ];

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }

    public function providers()
    {
        return $this->belongsTo(InsuranceProvider::class);
    }
}
