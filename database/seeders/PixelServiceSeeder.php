<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\PixelService;
use Illuminate\Database\Seeder;

class PixelServiceSeeder extends Seeder
{

    public function run(): void
    {
        $services = [
            [
                'name'      => 'Facebook',
                'image_url' => '/images/dashboard/pixelServices/facebook.svg',
                'status'    => StatusEnum::ACTIVE->name,
            ],
            [
                'name'      => 'Google ADS',
                'image_url' => '/images/dashboard/pixelServices/google_ads.svg',
                'status'    => StatusEnum::INACTIVE->name,
            ],
            [
                'name'      => 'Taboola',
                'image_url' => '/images/dashboard/pixelServices/taboola.svg',
                'status'    => StatusEnum::INACTIVE->name,
            ],
            [
                'name'      => 'Outbrain',
                'image_url' => '/images/dashboard/pixelServices/outbrain.svg',
                'status'    => StatusEnum::INACTIVE->name,
            ],
            [
                'name'      => 'Pinterest',
                'image_url' => '/images/dashboard/pixelServices/pinterest.svg',
                'status'    => StatusEnum::INACTIVE->name,
            ],
            [
                'name'      => 'TikTok',
                'image_url' => '/images/dashboard/pixelServices/tiktok.svg',
                'status'    => StatusEnum::INACTIVE->name,
            ],
        ];

        PixelService::upsert($services, ['name'], ['image_url', 'status']);

    }
}
