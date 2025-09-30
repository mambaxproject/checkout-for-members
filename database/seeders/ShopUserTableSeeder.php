<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShopUserTableSeeder extends Seeder
{

    public function run(): void
    {
        $users = User::cursor();

        foreach ($users as $key => $user) {
            $user->shops()->sync($user->id); // the ID of user is the ID of shop demo created by seed
        }
    }

}
