<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        return isset($config) && $config == 0 ? 's3' : 'public';
    }

    public static function upload(string $dir, string $format, $image = null)
    {
        try {
            if ($image !== null) {
                $extension = $image->getClientOriginalExtension() ?? $format;
                $imageName = Carbon::now()->toDateString() . '-' . uniqid() . '.' . $extension;
                $path = rtrim($dir, '/') . '/';

                if (!Storage::disk(self::getDisk())->exists($path)) {
                    Storage::disk(self::getDisk())->makeDirectory($path);
                }

                Storage::disk(self::getDisk())->putFileAs($path, $image, $imageName);
            } else {
                $imageName = 'def.png';
            }
        } catch (Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            $imageName = 'def.png';
        }

        return $imageName;
    }

    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if ($image === null) {
            return $old_image;
        }

        try {
            $oldPath = rtrim($dir, '/') . '/' . ltrim($old_image, '/');
            if (Storage::disk(self::getDisk())->exists($oldPath)) {
                Storage::disk(self::getDisk())->delete($oldPath);
            }
        } catch (Exception $e) {
            Log::error('Failed to delete old image: ' . $e->getMessage());
        }

        return self::upload($dir, $format, $image);
    }

    public static function check_and_delete(string $dir, $old_image)
    {
        $relativePath = rtrim($dir, '/') . '/' . ltrim($old_image, '/');

        foreach (['public', 's3'] as $disk) {
            try {
                if (Storage::disk($disk)->exists($relativePath)) {
                    Storage::disk($disk)->delete($relativePath);
                }
            } catch (Exception $e) {
                Log::error("Deletion failed on disk [$disk]: " . $e->getMessage());
            }
        }

        return true;
    }

    public static function get_full_url($path, $data, $placeholder = null)
    {
        $placeholders = [
            'default' => dynamicAsset('public/assets/images/product-img.png'),
            'admin' => dynamicAsset('public/assets/images/product-img.png'),
            'restaurant' => dynamicAsset('public/assets/images/product-img.png'),
        ];

        $fullPath = rtrim($path, '/') . '/' . ltrim($data, '/');

        if ($data && Storage::disk('public')->exists($fullPath)) {
            return dynamicStorage('storage/app/public') . '/' . $fullPath;
        }

        if (request()->is('api/*')) {
            return null;
        }

        return $placeholders[$placeholder] ?? $placeholders[$path] ?? $placeholders['default'];
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
