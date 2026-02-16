<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceProvider extends Model
{
    use SoftDeletes;
    protected $table = 'insurance_providers';
    protected $fillable = [
        'name',
        'address',
        'email',
        'phone_no',
        'status'
    ];
}
