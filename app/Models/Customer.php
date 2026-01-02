<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function renewals()
    {
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

    public static function validateData($data)
    {
        $rules = [
            // Customer fields
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],

            // Vehicle arrays
            'vehicle_types' => ['required', 'array', 'min:1'],
            'vehicle_types.*' => ['required', 'integer', 'exists:vehicle_types,id'],

            'vehicle_categories' => ['required', 'array'],
            'vehicle_categories.*' => ['required', 'integer', 'exists:vehicle_categories,id'],

            'registration_no' => ['required', 'array'],
            'registration_no.*' => ['required', 'string', 'max:255'],

            'permit_no' => ['required', 'array'],
            'permit_no.*' => ['required', 'string', 'max:255'],
        ];

        $messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'vehicle_types.*.required' => 'Vehicle type is required.',
            'registration_no.*.required' => 'Registration number is required.',
            'permit_no.*.required' => 'Permit number is required.',
        ];

        return Validator::make($data, $rules, $messages);
    }
}
