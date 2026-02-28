<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Renewal extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'vehicle_id',
        'renewal_type_id',
        'renewable_type',
        'renewable_id',

        'start_date_bs',
        'start_date_ad',
        'expiry_date_bs',
        'expiry_date_ad',

        'reminder_date',
        'remarks',
        'status',
        'is_paid',
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

    public static function validateData($data)
    {
        $rules = [
            // Bluebook fields
            'issue_date' => ['required', 'string', 'max:255'],
            'last_expiry_date' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
        ];

        $messages = [
            'issue_date.required' => 'First name is required.',
            'last_expiry_date.required' => 'Last name is required.',
            'expiry_date.required' => 'Vehicle type is required.',
        ];

        return Validator::make($data, $rules, $messages);
    }

    public function getVehicleAttribute()
    {
        return $this->renewable?->vehicle;
    }

    protected static function booted()
    {
        static::creating(function ($renewal) {
            $renewal->reminder_date = Carbon::parse($renewal->expiry_date_ad)->subDays(7);
        });
    }


    public function scopeExpired($query)
    {
        return $query->whereDate('start_date_ad', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereDate('start_date_ad', '<=', now()->addDays($days))
            ->whereDate('start_date_ad', '>=', now());
    }

    public function getIsExpiredAttribute()
    {
        return $this->start_date_ad ? now()->gt(Carbon::parse($this->start_date_ad)) : false;
    }

    public function getDisplayStatusAttribute()
    {
        if ($this->is_expired) {
            return 'expired';
        }

        return $this->status;
    }
}
