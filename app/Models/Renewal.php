<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    protected $fillable = [
        'vehicle_id',
        'customer_id',
        'status',
        'late_days',
        'base_fee',
        'penalty_fee',
        'service_fee',
        'total',
        'meta'
    ];
    
    protected $casts = [ 'meta' => 'array', 'requested_for_year' => 'date' ];
    
    public function vehicle(){ 
        return $this->belongsTo(Vehicle::class); 
    }

    public function customer(){ 
        return $this->belongsTo(Customer::class); 
    }

    public function documents(){ 
        return $this->hasMany(RenewalDocument::class); 
    }

    public function payments(){ 
        return $this->hasMany(Payment::class); 
    }
}
