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
        'status',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date' => 'string',
        'expiry_date' => 'string',
        'amount' => 'decimal:2',
    ];

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }

    public function renewals()
    {
        return $this->morphMany(Renewal::class, 'renewable');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function providers()
    {
        return $this->belongsTo(InsuranceProvider::class);
    }
}
