<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopTableSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id'          => 1,
                'owner_id'    => 1,
                'name'        => 'Loja Demo Admin',
                'description' => 'Loja demo para testes',
                'status'      => StatusEnum::ACTIVE->name,
            ],
            [
                'id'          => 2,
                'owner_id'    => 2,
                'name'        => 'Loja Demo #1',
                'description' => 'Loja demo para testes',
                'status'      => StatusEnum::ACTIVE->name,
            ],
            [
                'id'          => 3,
                'owner_id'    => 3,
                'name'        => 'Loja Demo #2',
                'description' => 'Loja demo para testes',
                'status'      => StatusEnum::ACTIVE->name,
            ],
            [
                'id'          => 4,
                'owner_id'    => 4,
                'name'        => 'Loja Demo #3',
                'description' => 'Loja demo para testes',
                'status'      => StatusEnum::ACTIVE->name,
            ],
        ];

        Shop::upsert(
            $rows,
            ['id'],
            ['owner_id', 'name', 'description', 'status']
        );

        Shop::all()->each->update(['attributes' => ['allowCreditCard' => true]]);
    }
}
