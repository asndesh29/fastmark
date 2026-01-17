<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VehicleType extends Model
{
    use SoftDeletes;
    protected $table = 'vehicle_types';

    protected $fillable = [
        'name',
        'slug',
        'is_active'
    ];

    protected $casts = [
        'name' => 'string',
        'is_active' => 'boolean'
    ];

    public static function validateData($data, $vehicleType = null)
    {
        // If a vehicleType is provided (for updating), exclude its current name
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                // For updating, allow the same name, but not if it's assigned to a different vehicle type
                $vehicleType ? Rule::unique('vehicle_types', 'name')->ignore($vehicleType->id) : 'unique:vehicle_types,name',
            ],
        ];

        $messages = [
            'name.required' => 'The vehicle type name is required.',
            'name.max' => 'The vehicle type name cannot be longer than 255 characters.',
            'name.unique' => 'The vehicle type name has already been taken.',
        ];

        return Validator::make($data, $rules, $messages);
    }

    // Create or update the slug
    public static function createSlug($name)
    {
        // Generate a slug from the 'name' attribute
        $slug = Str::slug($name);

        // Check if a slug already exists and make it unique
        $slugExists = self::where('slug', $slug)->exists();

        if ($slugExists) {
            // Append a unique number to the slug to make it unique
            $slug = $slug . '-' . time(); // Or you could use an incremental counter
        }

        return $slug;
    }

    // Boot method to generate the slug before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($vehicleType) {
            // Generate and assign the slug before saving
            if (empty($vehicleType->slug)) {
                $vehicleType->slug = self::createSlug($vehicleType->name);
            }
        });
    }
}
