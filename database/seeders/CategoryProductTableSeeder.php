<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\CategoryProduct;
use Illuminate\Database\Seeder;

class CategoryProductTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Desenvolvimento Pessoal',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Educacional',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Empreendedorismo digital',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Entretenimento',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Finanças e investimentos',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Hobbies',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Marketing e Vendas',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Moda e beleza',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Negócios e Carreia',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Saúde e esportes',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Tecnologia da Informação',
                'status' => StatusEnum::ACTIVE->name
            ],
            [
                'name' => 'Outros',
                'status' => StatusEnum::ACTIVE->name
            ],
        ];

        CategoryProduct::upsert($roles, ['id'], ['name']);
    }
}
