<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{

    public function run(): void
    {
        User::findOrFail(1)->roles()->sync(1);

        $idsUsersShop = [2, 3, 4];
        User::findOrFail($idsUsersShop)->each(function ($user) {
            $user->roles()->sync(3); // 3 = role 'Shop'
        });
    }

}
