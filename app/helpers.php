<?php

if (!function_exists('dynamicAsset')) {
    function dynamicAsset(string $directory): string
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $result = str_replace('public/', '', $directory);
        } else {
            $result = $directory;
        }
        return asset($result);
    }
}
if (!function_exists('dynamicStorage')) {
    function dynamicStorage(string $directory): string
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $result = str_replace('storage/app/public', 'storage', $directory);
        } else {
            $result = $directory;
        }
        return asset($result);
    }
}
