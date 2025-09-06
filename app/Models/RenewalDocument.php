<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenewalDocument extends Model
{
    protected $fillable = ['renewal_id','type','path','original_name'];
    
    public function renewal(){ 
        return $this->belongsTo(Renewal::class); 
    }
}
