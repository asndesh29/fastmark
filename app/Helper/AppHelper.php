<?php

namespace App\Helpers;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppHelper
{

    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }

    public static function error_formater($key, $mesage, $errors = [])
    {
        $errors[] = ['code' => $key, 'message' => $mesage];

        return $errors;
    }

    public static function get_business_settings($name, $json_decode = true)
    {
        $config = null;
        $settings = Cache::rememberForever('business_settings_all_data', function () {
            // return BusinessSetting::all();
            return true;
        });

        $data = $settings?->firstWhere('key', $name);

        if (isset($data)) {
            $config = $json_decode ? json_decode($data['value'], true) : $data['value'];
            if (is_null($config)) {
                $config = $data['value'];
            }
        }
        return $config;
    }


    /**
     * Get the storage disk for the given model.
     *
     * @return string
     */
    public static function getDisk()
    {
        $config = self::get_business_settings('local_storage');
        return isset($config) ? ($config == 0 ? 's3' : 'public') : 'public';
    }


    public static function upload(string $dir, string $format, $image = null)
    {
        // dd($image);
        try {
            if ($image != null) {
                $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
                // dd(Storage::disk(self::getDisk()));
                if (!Storage::disk(self::getDisk())->exists($dir)) {
                    Storage::disk(self::getDisk())->makeDirectory($dir);
                }
                // dd(2);
                Storage::disk(self::getDisk())->putFileAs($dir, $image, $imageName);

            } else {
                $imageName = 'def.png';
            }
        } catch (Exception $e) {
        }
        // dd($imageName);
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
        } catch (\Exception $e) {
        }

        return true;
    }

    public static function get_full_url($path, $data, $placeholder = null)
    {
        $place_holders = [
            'default' => dynamicAsset('public/assets/images/product-img.png'),
            'admin' => dynamicAsset('public/assets/images/product-img.png'),
            'restaurant' => dynamicAsset('public/assets/images/product-img.png'),
        ];

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

        // return 'def.png';
    }

    public static function time_date_format($data)
    {
        $time = config('timeformat') ?? 'H:i';
        return Carbon::parse($data)->locale(app()->getLocale())->translatedFormat('d M Y ' . $time);
    }
    public static function date_format($data)
    {
        return Carbon::parse($data)->locale(app()->getLocale())->translatedFormat('d M Y');
    }
    public static function time_format($data)
    {
        $time = config('timeformat') ?? 'H:i';
        return Carbon::parse($data)->locale(app()->getLocale())->translatedFormat($time);
    }
}