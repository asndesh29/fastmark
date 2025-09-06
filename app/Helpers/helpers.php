<?php

use App\Helpers\AssetHelper;

if (!function_exists('dynamicAsset')) {
    function dynamicAsset(string $path): string
    {
        return AssetHelper::dynamicAsset($path);
    }
}

if (!function_exists('dynamicStorage')) {
    function dynamicStorage(string $path): string
    {
        return AssetHelper::dynamicStorage($path);
    }
}

if (!function_exists('get_full_url')) {
    function get_full_url($path, $data, $placeholder = null)
    {
        return AppHelper::get_full_url($path, $data, $placeholder);
    }
}