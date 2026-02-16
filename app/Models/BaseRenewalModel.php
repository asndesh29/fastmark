<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

abstract class BaseRenewalModel extends Model
{
    use SoftDeletes;

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date_ad' => 'date',
        'expiry_date_ad' => 'date',
        'renewed_expiry_date_ad' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }

    public function renewals()
    {
        return $this->morphMany(Renewal::class, 'renewable');
    }

    /*
    |--------------------------------------------------------------------------
    | Expiry Logic
    |--------------------------------------------------------------------------
    */

    public function getFinalExpiryAdAttribute()
    {
        return $this->renewed_expiry_date_ad ?? $this->expiry_date_ad;
    }

    public function getFinalExpiryBsAttribute()
    {
        return $this->renewed_expiry_date_bs ?? $this->expiry_date_bs;
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->final_expiry_ad) {
            return false;
        }

        return Carbon::parse($this->final_expiry_ad)->isPast();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeExpired($query)
    {
        return $query->whereNotNull('renewed_expiry_date_ad')
            ->where('renewed_expiry_date_ad', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('renewed_expiry_date_ad')
            ->whereBetween('renewed_expiry_date_ad', [
                now(),
                now()->addDays($days)
            ]);
    }
}
