<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'customer_id',
        'vehicle_type_id',
        'registration_no',
        'chassis_no',
        'engine_no',
        'type',
        'engine_cc',
        'last_renewed_at',
        'expiry_date',
        'is_active'
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'vehicle_type_id' => 'integer'
    ];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function owner()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function renewals()
    {
        return $this->hasMany(Renewal::class);
    }
}
