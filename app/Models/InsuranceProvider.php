<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceProvider extends Model
{
    protected $table = 'insurance_providers';
    protected $fillable = [
        'name',
        'address',
        'email',
        'phone_no',
        'status'
    ];
}
