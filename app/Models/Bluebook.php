<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bluebook extends Model
{
    protected $fillable = [
        'vehicle_id',
        'book_number',
        'issue_date',
        'last_renewed_at',
        'expiry_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date' => 'date',
        'last_renewed_at' => 'date',
        'expiry_date' => 'date',
    ];

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }
}
