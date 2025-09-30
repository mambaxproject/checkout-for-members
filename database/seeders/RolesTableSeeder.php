<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Admin',
            ],
            [
                'id'    => 2,
                'title' => 'User',
            ],
            [
                'id'    => 3,
                'title' => 'Shop',
            ],
            [
                'id'    => 4,
                'title' => 'Affiliate',
            ],
            [
                'id'    => 5,
                'title' => 'Co-producer',
            ],
        ];

        Role::upsert($roles, ['id'], ['title']);
    }
}
