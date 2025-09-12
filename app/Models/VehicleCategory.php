<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCategory extends Model
{
    protected $table = 'vehicle_categories';
    protected $fillable = [
        'name',
        'is_active'
    ];

    protected $casts = [
        'name' => 'string',
        'is_active' => 'boolean',
    ];
}
