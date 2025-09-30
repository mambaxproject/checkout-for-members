<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            CategoryProductTableSeeder::class,
            ShopTableSeeder::class,
            ShopUserTableSeeder::class,
            PixelServiceSeeder::class,
            AppSeeder::class,
            WebhookEventsSeeder::class,
            NotificationEventSeeder::class,
            NotificationTypeSeeder::class
        ]);
    }
}
