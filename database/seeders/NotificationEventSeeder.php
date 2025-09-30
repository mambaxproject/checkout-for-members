<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationEventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notification_events')->insert([
            [
                'name' => 'Carrinho Abandonado',
                'desc' => 'Notificação enviada quando um usuário abandona o carrinho de compras.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Criação Boleto/Pix',
                'desc' => 'Notificação enviada quando um boleto ou pix é gerado para o usuário.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Confirmação de Pagamento Boleto/Pix',
                'desc' => 'Notificação enviada quando o pagamento de boleto ou pix é confirmado.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Confirmação de Pagamento Cartão',
                'desc' => 'Notificação enviada quando o pagamento com cartão é confirmado.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Confirmação de Pagamento Erro de Pagamento',
                'desc' => 'Notificação enviada quando ocorre um erro no pagamento.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
