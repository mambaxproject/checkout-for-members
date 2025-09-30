<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id'                 => 1,
                'name'               => 'Admin',
                'email'              => 'admin@admin.com',
                'password'           => bcrypt('password'),
                'remember_token'     => null,
                'verified'           => 1,
                'verified_at'        => '2023-10-30 18:02:26',
            ],
            [
                'id'                 => 2,
                'name'               => 'Lojista Demo #1',
                'email'              => 'lojista@demo.com',
                'password'           => bcrypt('12345678'),
                'remember_token'     => null,
                'verified'           => 1,
                'verified_at'        => now(),
            ],
            [
                'id'                 => 3,
                'name'               => 'Lojista Demo #2',
                'email'              => 'lojista2@demo.com',
                'password'           => bcrypt('12345678'),
                'remember_token'     => null,
                'verified'           => 1,
                'verified_at'        => now(),
            ],
            [
                'id'                 => 4,
                'name'               => 'Lojista Demo #3',
                'email'              => 'lojista3@demo.com',
                'password'           => bcrypt('12345678'),
                'remember_token'     => null,
                'verified'           => 1,
                'verified_at'        => now(),
            ],
        ];

        User::upsert(
            $users,
            ['id'],
            ['name', 'email', 'password', 'remember_token', 'verified', 'verified_at', 'verification_token', 'two_factor_code', 'phone_number', 'document_number']
        );
    }
}
