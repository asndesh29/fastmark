<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    protected $fillable = [
        'vehicle_id',
        'renewal_type_id',
        'renewable_type',
        'renewable_id',
        'start_date',
        'expiry_date',
        'reminder_date',
        'remarks',
        'status',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function renewable()
    {
        return $this->morphTo();
    }

    public function renewalType()
    {
        return $this->belongsTo(RenewalType::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'payment_renewal')
            ->withPivot('amount')
            ->withTimestamps();
    }

}
