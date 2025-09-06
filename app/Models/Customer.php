<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table = 'customers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'image',
        'is_active',
    ];

    protected $appends = ['image_full_url'];

    public function vehicles(){ 
        return $this->hasMany(Vehicle::class); 
    }

    public function renewals(){ 
        return $this->hasMany(Renewal::class); 
    }

    public function getImageFullUrlAttribute()
    {
        $value = $this->image;
        if (count($this->storage) > 0) {
            foreach ($this->storage as $storage) {
                if ($storage['key'] == 'image') {
                    return AppHelper::get_full_url('category', $value, $storage['value']);
                }
            }
        }

        return AppHelper::get_full_url('category', $value, 'public');
    }
    public function storage()
    {
        return $this->morphMany(Storage::class, 'data');
    }

    protected static function boot()
    {
        parent::boot();
        // static::created(function ($category) {
        //     $category->slug = $category->generateSlug($category->name);
        //     $category->save();
        // });
        static::saved(function ($model) {
            if ($model->isDirty('image')) {
                $value = AppHelper::getDisk();

                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => 'image',
                ], [
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }


}
