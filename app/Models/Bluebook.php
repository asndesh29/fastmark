<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Bluebook extends Model
{
    use SoftDeletes;
    protected $table = 'blue_books';
    protected $fillable = [
        'vehicle_id',
        'invoice_no',
        'issue_date',
        'last_expiry_date',
        'expiry_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date' => 'string',
        'last_expiry_date' => 'string',
        'expiry_date' => 'string',
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
        $rules = [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            // 'issue_date' => ['required', 'string', 'max:255'],
            'last_expiry_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:paid,unpaid'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string']
        ];

        $messages = [
            // 'issue_date.required' => 'Issue Date is required.',
            'last_expiry_date.required' => 'Expiry Date is required.',
        ];

        return Validator::make($data, $rules, $messages);
    }
}
