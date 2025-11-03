<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiclePass extends Model
{
    protected $table = 'vehicle_passes';

    protected $fillable = [
        'vehicle_id',
        'invoice_no',
        'last_expiry_date',
        'issue_date',
        'expiry_date',
        'tax_amount',
        'renewal_charge',
        'income_tax',
        'remarks',
        'status'
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'last_expiry_date' => 'string',
        'issue_date' => 'string',
        'expiry_date' => 'string'
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
}
