<?php

namespace App\Helpers;

class StorageUrl
{
    public static function getStorageUrlS3(): string
    {
        return config('services.awsUrl') . '/';
    }
}
