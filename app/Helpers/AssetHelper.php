<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class AssetHelper
{
    public static function dynamicAsset(string $path): string
    {
        if (empty($path)) {
            throw new \InvalidArgumentException('Asset path cannot be empty.');
        }

        $adjustedPath = config('app.domain_pointed_directory') === 'public'
            ? ltrim(str_replace('public/', '', $path), '/')
            : ltrim($path, '/');

        return asset($adjustedPath);
    }

    public static function dynamicStorage(string $path): string
    {
        if (empty($path)) {
            throw new \InvalidArgumentException('Storage path cannot be empty.');
        }

        if (config('app.domain_pointed_directory') === 'public') {
            $adjustedPath = str_replace('storage/app/public', 'storage', $path);
            return asset(ltrim($adjustedPath, '/'));
        }

        return Storage::url($path);
    }
}
