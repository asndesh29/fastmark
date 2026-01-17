<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class PollutionCheck extends Model
{
    use SoftDeletes;
    protected $table = 'pollution_checks';

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

    protected $attributes = [
        'tax_amount' => 0,
        'renewal_charge' => 0,
        'income_tax' => 0,
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

    public static function validateData($data)
    {
        $data['tax_amount'] = $data['tax_amount'] ?? 0;
        $data['renewal_charge'] = $data['renewal_charge'] ?? 0;
        $data['income_tax'] = $data['income_tax'] ?? 0;

        $rules = [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'type' => ['nullable', 'string'],
            'invoice_number' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['required', 'string', 'max:255'],
            'last_expiry_date' => ['required', 'string', 'max:255'],
            'tax_amount' => ['nullable', 'numeric'],
            'renewal_charge' => ['nullable', 'numeric'],
            'income_tax' => ['nullable', 'numeric'],
            'status' => ['required', 'in:paid,unpaid'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];

        $messages = [
            'issue_date.required' => 'Issue Date is required.',
            'last_expiry_date.required' => 'Last Expiry Date is required.',
        ];

        return Validator::make($data, $rules, $messages);
    }
}
