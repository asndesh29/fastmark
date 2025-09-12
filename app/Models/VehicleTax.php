<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTax extends Model
{
    protected $table = 'vehicle_taxes';
    protected $fillable = [
        'vehicle_id',
        'tax_year',
        'last_renewed_at',
        'expiry_date',
        'amount',
        'status',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'last_renewed_at' => 'date',
        'expiry_date' => 'date',
    ];

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }
}
