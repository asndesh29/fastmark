<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Insurance extends Model
{
    protected $fillable = [
        'vehicle_id',
        'provider_id',
        'policy_number',
        'issue_date',
        'expiry_date',
        'amount',
        'status',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date' => 'string',
        'expiry_date' => 'string',
        'amount' => 'decimal:2',
    ];

    protected $attributes = [
        'amount' => 0,
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

        $rules = [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'type' => ['nullable', 'string'],
            'provider_id' => ['required', 'exists:insurance_providers,id'],
            'policy_number' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['required', 'string', 'max:255'],
            'expiry_date' => ['nullable', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric'],
            'status' => ['required', 'in:paid,unpaid'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];

        $messages = [
            'issue_date.required' => 'Issue Date is required.',
        ];

        return Validator::make($data, $rules, $messages);
    }
}
