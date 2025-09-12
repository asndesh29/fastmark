<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeSlab extends Model
{
    protected $fillable = [
        'vehicle_type_id',
        'min_cc',
        'max_cc',
        'base_fee',
    ];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeType($query, $type)
    {
        return $query->where('vehicle_type', $type);
    }

    public function scopeCC($query, $cc)
    {
        return $query->where('min_cc', '<=', $cc)->where('max_cc', '>=', $cc);
    }

    public function scopeActiveCC($query, $cc)
    {
        return $query->where('min_cc', '<=', $cc)->where('max_cc', '>=', $cc)->where('is_active', true);
    }

    public function scopeActiveType($query, $type)
    {
        return $query->where('vehicle_type', $type)->where('is_active', true);
    }

    public function scopeActiveTypeCC($query, $type, $cc)
    {
        return $query->where('vehicle_type', $type)->where('min_cc', '<=', $cc)->where('max_cc', '>=', $cc)->where('is_active', true);
    }

    public function scopeActiveTypeCCFee($query, $type, $cc, $fee)
    {
        return $query->where('vehicle_type', $type)->where('min_cc', '<=', $cc)->where('max_cc', '>=', $cc)->where('base_fee', $fee)->where('is_active', true);
    }

    public function scopeActiveTypeCCFeeLate($query, $type, $cc, $fee, $late)
    {
        return $query->where('vehicle_type', $type)->where('min_cc', '<=', $cc)->where('max_cc', '>=', $cc)->where('base_fee', $fee)->where('late_per_day', $late)->where('is_active', true);
    }


}
