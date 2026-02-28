<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class VehicleTax extends Model
{
    use SoftDeletes;
    protected $table = 'vehicle_taxes';

    protected $fillable = [
        'vehicle_id',
        'invoice_no',
        'issue_date_bs',
        'issue_date_ad',
        'expiry_date_bs',
        'expiry_date_ad',
        'renewed_expiry_date_bs',
        'renewed_expiry_date_ad',
        'tax_amount',
        'renewal_charge',
        'income_tax',
        'total_amount',
        'payment_status',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date_ad' => 'date',
        'expiry_date_ad' => 'date',
        'renewed_expiry_date_ad' => 'date',
    ];

    protected $attributes = [
        'tax_amount' => 0,
        'renewal_charge' => 0,
        'income_tax' => 0,
    ];

    public function renewals()
    {
        return $this->morphMany(Renewal::class, 'renewable');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public static function validateData($data)
    {
        $data['tax_amount'] = $data['tax_amount'] ?? 0;
        $data['renewal_charge'] = $data['renewal_charge'] ?? 0;
        $data['income_tax'] = $data['income_tax'] ?? 0;

        $rules = [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'renewable_type' => ['required', 'string', 'max:255'],
            'expiry_date_bs' => ['required', 'string', 'max:255'],
            'payment_status' => ['required', 'in:paid,unpaid'],
            'remarks' => ['nullable', 'string', 'max:255']
        ];

        $messages = [
            'vehicle_id.required' => 'Vehicle is required.',
            'expiry_date_bs.required' => 'Expiry Date is required.',
            'payment_status.required' => 'Payment Status is required.',
        ];


        return Validator::make($data, $rules, $messages);
    }

    public function latestRenewal()
    {
        return $this->morphOne(Renewal::class, 'renewable')->latestOfMany();
    }
}

