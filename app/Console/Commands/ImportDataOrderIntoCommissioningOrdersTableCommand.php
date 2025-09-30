<?php

namespace App\Console\Commands;

use App\Actions\SaveDataCommissioningOrder;
use App\Models\{Order, Shop};
use Illuminate\Console\Command;

class ImportDataOrderIntoCommissioningOrdersTableCommand extends Command
{
    protected $signature = 'import:data-order-into-commissioning-orders-table';

    protected $description = 'Importa dados de pedidos com split (comissões) na nova tabela de comissionamento para afiliados e coprodutores';

    public function handle(): void
    {
        $ordersWithSplit = Order::with('affiliate')
            ->whereNotNull('attributes->splitGateway')
            ->cursor();

        $this->withProgressBar($ordersWithSplit, function () use ($ordersWithSplit) {
            foreach ($ordersWithSplit as $order) {
                $splitGateways = $order->getValueSchemalessAttributes('splitGateway');

                if (blank($splitGateways)) {
                    continue;
                }

                $usernames = collect($splitGateways)->pluck('username')->unique()->filter()->all();
                $shopIds   = Shop::whereIn('username_banking', $usernames)->pluck('owner_id', 'username_banking');

                foreach ($splitGateways as $split) {
                    $commissionedId = $shopIds[$split['username']] ?? null;

                    if (! $commissionedId) {
                        continue;
                    }

                    (new SaveDataCommissioningOrder($order, $commissionedId, $split))->handle();
                }
            }
        });
    }
}
