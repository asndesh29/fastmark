<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bluebook extends Model
{
    protected $table = 'blue_books';
    protected $fillable = [
        'vehicle_id',
        'book_number',
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
}
