<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppHelper
{
    public static function error_processor($validator)
    {
        return collect($validator->errors()->getMessages())->map(function ($messages, $field) {
            return ['code' => $field, 'message' => $messages[0]];
        })->values()->all();
    }

    public static function error_formater($key, $message, $errors = [])
    {
        $errors[] = ['code' => $key, 'message' => $message];
        return $errors;
    }

    public static function get_business_settings($name, $json_decode = true)
    {
        $settings = Cache::rememberForever('business_settings_all_data', function () {
            return BusinessSetting::all();
        });

        $data = $settings?->firstWhere('key', $name);

        if (!isset($data)) {
            return null;
        }

        $config = $json_decode ? json_decode($data['value'], true) : $data['value'];

        return is_null($config) ? $data['value'] : $config;
    }

    public static function getDisk()
    {
        $config = self::get_business_settings('local_storage');

        return isset($config) ? ($config == 0 ? 's3' : 'public') : 'public';
    }

    public static function upload(string $dir, string $format, $image = null)
    {
        try {
            if ($image != null) {
                $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
                if (!Storage::disk(self::getDisk())->exists($dir)) {
                    Storage::disk(self::getDisk())->makeDirectory($dir);
                }
                Storage::disk(self::getDisk())->putFileAs($dir, $image, $imageName);
            } else {
                $imageName = 'def.png';
            }
        } catch (Exception $e) {
        }
        return $imageName;
    }

    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if ($image == null) {
            return $old_image;
        }
        try {
            if (Storage::disk(self::getDisk())->exists($dir . $old_image)) {
                Storage::disk(self::getDisk())->delete($dir . $old_image);
            }
        } catch (Exception $e) {
        }
        $imageName = AppHelper::upload($dir, $format, $image);
        return $imageName;
    }

    public static function check_and_delete(string $dir, $old_image)
    {

        try {
            if (Storage::disk('public')->exists($dir . $old_image)) {
                Storage::disk('public')->delete($dir . $old_image);
            }
            if (Storage::disk('s3')->exists($dir . $old_image)) {
                Storage::disk('s3')->delete($dir . $old_image);
            }
        } catch (Exception $e) {
        }

        return true;
    }

    public static function get_full_url($path, $data, $type, $placeholder = null)
    {
        $place_holders = [
            'default' => dynamicAsset('assets/admin/img/100x100/no-image-found.png'),
            'admin' => dynamicAsset('assets/admin/img/160x160/img1.jpg'),
        ];

        try {
            if ($data && $type == 's3' && Storage::disk('s3')->exists($path . '/' . $data)) {
                return Storage::disk('s3')->url($path . '/' . $data);
            }
        } catch (Exception $e) {
        }

        if ($data && Storage::disk('public')->exists($path . '/' . $data)) {
            return dynamicStorage('storage/app/public') . '/' . $path . '/' . $data;
        }

        if (request()->is('api/*')) {
            return null;
        }

        if (isset($placeholder) && array_key_exists($placeholder, $place_holders)) {
            return $place_holders[$placeholder];
        } elseif (array_key_exists($path, $place_holders)) {
            return $place_holders[$path];
        } else {
            return $place_holders['default'];
        }

        return 'def.png';
    }

    public static function time_date_format($data)
    {
        $time = config('timeformat', 'H:i');
        return Carbon::parse($data)->locale(app()->getLocale())->translatedFormat('d M Y ' . $time);
    }

    public static function date_format($data)
    {
        return Carbon::parse($data)->locale(app()->getLocale())->translatedFormat('d M Y');
    }

    public static function time_format($data)
    {
        $time = config('timeformat', 'H:i');
        return Carbon::parse($data)->locale(app()->getLocale())->translatedFormat($time);
    }
}
