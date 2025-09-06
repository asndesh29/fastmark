<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $table = 'permissions';

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }
}
