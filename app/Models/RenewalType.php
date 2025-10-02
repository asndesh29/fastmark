<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenewalType extends Model
{
    protected $table = 'renewal_types';

    protected $fillable = [
        'name',
        'charge',
        'is_active'
    ];

    protected $casts = [
        'name' => 'string',
        'is_active' => 'boolean'
    ];
}
