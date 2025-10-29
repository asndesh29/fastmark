<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceProvider extends Model
{
    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'status'
    ];
}
