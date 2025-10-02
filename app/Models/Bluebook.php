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
        'last_renewed_at',
        'expiry_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'issue_date' => 'string',
        'last_renewed_at' => 'string',
        'expiry_date' => 'string',
    ];

    public function renewal()
    {
        return $this->morphOne(Renewal::class, 'renewable');
    }
}
