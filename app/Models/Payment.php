<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = ['renewal_id','gateway','status','reference','amount','payload'];
    
    protected $casts = ['payload'=>'array'];
    
    public function renewal(){ 
        return $this->belongsTo(Renewal::class); 
    }
}
