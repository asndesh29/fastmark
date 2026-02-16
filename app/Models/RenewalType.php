<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RenewalType extends Model
{
    use SoftDeletes;
    protected $table = 'renewal_types';

    protected $fillable = [
        'name',
        'slug',
        'private_validity_value',
        'private_validity_unit',
        'commercial_validity_value',
        'commercial_validity_unit',
        'is_active'
    ];

    protected $casts = [
        'name' => 'string',
        'is_active' => 'boolean'
    ];

    /**
     * All renewals for this type
     */
    public function renewals()
    {
        return $this->hasMany(\App\Models\Renewal::class, 'renewal_type_id');
    }


    protected static function boot()
    {
        parent::boot();
        static::created(function ($renewalType) {
            $renewalType->slug = $renewalType->generateSlug($renewalType->name);
            $renewalType->save();
        });

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

    private function generateSlug($name)
    {
        $slug = Str::slug($name);
        if ($max_slug = static::where('slug', 'like', "{$slug}%")->latest('id')->value('slug')) {

            if ($max_slug == $slug)
                return "{$slug}-2";

            $max_slug = explode('-', $max_slug);
            $count = array_pop($max_slug);
            if (isset($count) && is_numeric($count)) {
                $max_slug[] = ++$count;
                return implode('-', $max_slug);
            }
        }
        return $slug;
    }

    public static function validateData($data, $id = null)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('renewal_types', 'name')->ignore($id),
            ],

            'private_validity_unit' => 'required_with:private_validity_value|nullable|in:days,months,years',
            'private_validity_value' => 'required_with:private_validity_unit|nullable|integer|min:1',

            'commercial_validity_unit' => 'required_with:commercial_validity_value|nullable|in:days,months,years',
            'commercial_validity_value' => 'required_with:commercial_validity_unit|nullable|integer|min:1',
        ];

        $messages = [
            'name.required' => 'Renewal Type Name is required.',
            'name.unique' => 'This Renewal Type already exists.',
        ];

        return Validator::make($data, $rules, $messages);
    }

    public function getValidityForVehicle($vehicle)
    {
        if ($vehicle->isCommercial()) {
            return [
                'value' => $this->commercial_validity_value,
                'unit' => $this->commercial_validity_unit,
            ];
        }

        return [
            'value' => $this->private_validity_value,
            'unit' => $this->private_validity_unit,
        ];
    }


}


