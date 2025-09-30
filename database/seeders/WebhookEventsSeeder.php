<?php

namespace Database\Seeders;

use App\Models\WebhookEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebhookEventsSeeder extends Seeder
{

    public function run(): void
    {
        $eventsWebHook = [
            ['name' => 'Pedido criado'],
            ['name' => 'Pedido atualizado'],
            ['name' => 'Pagamento autorizado'],
            ['name' => 'Pagamento recusado'],
            ['name' => 'Cliente criado'],
            ['name' => 'Cliente atualizado'],
            ['name' => 'Produto criado'],
            ['name' => 'Produto atualizado'],
            ['name' => 'Carrinho abandonado criado'],
        ];

        WebhookEvent::upsert($eventsWebHook, ['name']);
    }
}
