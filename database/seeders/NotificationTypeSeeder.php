<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notification_types')->insert([
            [
                'name_type' => 'whatsapp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_type' => 'sms',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_type' => 'email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
