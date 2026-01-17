<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VehicleCategory extends Model
{
    use SoftDeletes;
    protected $table = 'vehicle_categories';
    protected $fillable = [
        'name',
        'slug',
        'is_active'
    ];

    protected $casts = [
        'name' => 'string',
        'is_active' => 'boolean',
    ];

    public static function validateData($data, $vehicleCategory = null)
    {
        // If a vehicleType is provided (for updating), exclude its current name
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                // For updating, allow the same name, but not if it's assigned to a different vehicle type
                $vehicleCategory ? Rule::unique('vehicle_categories', 'name')->ignore($vehicleCategory->id) : 'unique:vehicle_categories,name',
            ],
        ];

        $messages = [
            'name.required' => 'The vehicle category name is required.',
            'name.max' => 'The vehicle category name cannot be longer than 255 characters.',
            'name.unique' => 'The vehicle category name has already been taken.',
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

        static::saving(function ($vehicleCategory) {
            // Generate and assign the slug before saving
            if (empty($vehicleCategory->slug)) {
                $vehicleCategory->slug = self::createSlug($vehicleCategory->name);
            }
        });
    }
}
