<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Vehicle extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'customer_id',
        'vehicle_type_id',
        'vehicle_category_id',
        'registration_no',
        'permit_no',
        'chassis_no',
        'engine_no',
        'engine_cc',
        'capacity',
        'is_active'
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'vehicle_type_id' => 'integer',
        'vehicle_category_id' => 'integer'
    ];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function vehicleCategory()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }

    public function owner()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function renewals()
    {
        return $this->hasMany(Renewal::class);
    }

    public function bluebook()  // latest one
    {
        return $this->hasOne(Bluebook::class)->latestOfMany();
    }

    public function pollution()
    {
        return $this->hasOne(PollutionCheck::class)->latestOfMany();
    }

    public function roadPermit()
    {
        return $this->hasOne(RoadPermit::class)->latestOfMany();
    }

    public function insurance()
    {
        return $this->hasOne(Insurance::class)->latestOfMany();
    }

    public function vehicleTax()
    {
        return $this->hasOne(VehicleTax::class)->latestOfMany();
    }

    public function vehiclePass()
    {
        return $this->hasOne(VehiclePass::class)->latestOfMany();
    }

    // Vehicle.php

    public static function validateData($data)
    {
        $rules = [
            'registration_no' => ['required', 'string', 'max:255'],
            'permit_no' => ['nullable', 'string', 'max:255'],
            'chassis_no' => ['nullable', 'string', 'max:255'],
            'engine_no' => ['nullable', 'string', 'max:255'],
            'engine_cc' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer'],
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'vehicle_category_id' => ['required', 'exists:vehicle_categories,id'],
        ];

        $messages = [
            'registration_no.required' => 'Registration No. is required.',
            'vehicle_type_id.required' => 'Vehicle Type is required.',
            'vehicle_category_id.required' => 'Vehicle Category is required.',
        ];

        return Validator::make($data, $rules, $messages);
    }

    public function isCommercial(): bool
    {
        return optional($this->vehicleCategory)->slug === 'commercial';
    }



}
