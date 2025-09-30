<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_types')->insert([
            [
                'id' => 1,
                'name' => 'Quero Apenas Receber Pagamentos',
                'label' => 'default',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Área de Membros da Suitpay',
                'label' => 'suitMembers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
