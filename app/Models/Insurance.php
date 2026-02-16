<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Insurance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'invoice_no',
        'provider_id',
        'policy_number',
        'issue_date_bs',
        'issue_date_ad',
        'expiry_date_bs',
        'expiry_date_ad',
        'renewed_expiry_date_bs',
        'renewed_expiry_date_ad',
        'insurance_type',
        'renewal_charge',
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
        'renewal_charge' => 0,
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

    public static function validateData($data)
    {
        $data['amount'] = $data['amount'] ?? 0;

        // $rules = [
        //     'vehicle_id' => ['required', 'exists:vehicles,id'],
        //     'type' => ['nullable', 'string'],
        //     'provider_id' => ['required', 'exists:insurance_providers,id'],
        //     'policy_number' => ['nullable', 'string', 'max:255'],
        //     'issue_date' => ['required', 'string', 'max:255'],
        //     'expiry_date' => ['nullable', 'string', 'max:255'],
        //     'amount' => ['nullable', 'numeric'],
        //     'status' => ['required', 'in:paid,unpaid'],
        //     'remarks' => ['nullable', 'string', 'max:255'],
        // ];

        // $messages = [
        //     'issue_date.required' => 'Issue Date is required.',
        // ];

        $rules = [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'provider_id' => ['required', 'exists:insurance_providers,id'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'expiry_date_bs' => ['required', 'string', 'max:255'],
            'insurance_type' => ['required', 'in:general, third'],
            'policy_number' => ['nullable', 'string', 'max:255'],
            'payment_status' => ['required', 'in:paid,unpaid'],
            'remarks' => ['nullable', 'string', 'max:255']
        ];

        $messages = [
            'vehicle_id.required' => 'Vehicle is required.',
            'provider_id.required' => 'Insurance Provider is required.',
            'expiry_date_bs.required' => 'Expiry Date is required.',
            'payment_status.required' => 'Payment Status is required.',
            'insurance_type.required' => 'Insurance Type is required.',
        ];

        return Validator::make($data, $rules, $messages);
    }
}
