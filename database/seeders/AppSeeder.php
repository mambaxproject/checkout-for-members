<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\App;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    public function run(): void
    {
        $apps = [
            [
                'name'        => 'Active Campaign',
                'slug'        => 'active-campaign',
                'description' => 'Active Campaign',
                'status'      => StatusEnum::ACTIVE->name,
                'icon_url'    => 'images/dashboard/apps/img-app-1.png',
            ],
            [
                'name'        => 'Google Tag Manager',
                'slug'        => 'google-tag-manager',
                'description' => 'Google Tag Manager',
                'status'      => StatusEnum::ACTIVE->name,
                'icon_url'    => 'images/dashboard/apps/img-app-2.png',
            ],
            [
                'name'        => 'Google Analytics',
                'slug'        => 'google-analytics',
                'description' => 'Google Analytics',
                'status'      => StatusEnum::ACTIVE->name,
                'icon_url'    => 'images/dashboard/apps/img-app-3.png',
            ],
            [
                'name'        => 'MemberKit',
                'slug'        => 'member-kit',
                'description' => 'MemberKit',
                'status'      => StatusEnum::ACTIVE->name,
                'icon_url'    => 'images/dashboard/apps/img-app-4.png',
            ],
            [
                'name'        => 'Reportana',
                'slug'        => 'reportana',
                'description' => 'Reportana',
                'status'      => StatusEnum::ACTIVE->name,
                'icon_url'    => 'images/dashboard/apps/img-app-5.png',
            ],
            [
                'name'        => 'Chat',
                'slug'        => 'chat',
                'description' => 'Chat',
                'status'      => StatusEnum::INACTIVE->name,
                'icon_url'    => 'images/dashboard/apps/img-app-6.png',
            ],
            [
                'name'        => 'WooCommerce',
                'slug'        => 'woocommerce',
                'description' => 'WooCommerce',
                'status'      => StatusEnum::ACTIVE->name,
                'icon_url'    => 'images/dashboard/apps/img-app-7.png',
            ],
            [
                'name'        => 'BotConversa',
                'slug'        => 'botconversa',
                'description' => 'BotConversa',
                'status'      => StatusEnum::ACTIVE->name,
                'icon_url'    => 'images/dashboard/apps/img-app-8.png',
            ],
            [
                'name'        => 'Utmify',
                'slug'        => 'utmify',
                'description' => 'Utmify',
                'status'      => StatusEnum::ACTIVE->name,
                'icon_url'    => 'images/dashboard/apps/img-app-10.png',
            ],
        ];

        foreach ($apps as $app) {
            App::query()->updateOrCreate(
                ['slug' => $app['slug']],
                $app
            );
        }
    }
}
