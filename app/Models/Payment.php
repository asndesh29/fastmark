<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    protected $fillable = ['renewal_id', 'gateway', 'status', 'reference', 'amount', 'payload'];

    protected $casts = ['payload' => 'array'];

    public function renewal()
    {
        return $this->belongsTo(Renewal::class);
    }

    public function renewals()
    {
        return $this->belongsToMany(Renewal::class, 'payment_renewal')
            ->withPivot('amount')
            ->withTimestamps();
    }
}
