<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class AssetHelper
{
    /**
     * Generate an asset URL, adjusting the path based on configuration.
     *
     * @param string $path The asset path (e.g., 'css/app.css')
     * @return string The resolved asset URL
     * @throws \InvalidArgumentException If the path is empty
     */
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

    /**
     * Generate a storage URL, adjusting the path based on configuration.
     *
     * @param string $path The storage path (e.g., 'public/images/example.jpg')
     * @return string The resolved storage URL
     * @throws \InvalidArgumentException If the path is empty
     */
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

// Global helper functions for convenience
if (!function_exists('dynamicAsset')) {
    /**
     * Helper function to access dynamicAsset via global scope.
     *
     * @param string $path
     * @return string
     */
    function dynamicAsset(string $path): string
    {
        return AssetHelper::dynamicAsset($path);
    }
}

if (!function_exists('dynamicStorage')) {
    /**
     * Helper function to access dynamicStorage via global scope.
     *
     * @param string $path
     * @return string
     */
    function dynamicStorage(string $path): string
    {
        return AssetHelper::dynamicStorage($path);
    }
}