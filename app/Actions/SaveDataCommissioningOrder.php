<?php

namespace App\Actions;

use App\Models\Order;

class SaveDataCommissioningOrder
{
    public function __construct(
        public readonly Order $order,
        public int $commissionedId,
        public array $split
    ) {}

    public function handle(): array
    {
        if (blank($this->split['username']) || blank($this->split['valueSplit']) || blank($this->split['splitTypePerson'])) {
            return [];
        }

        if (! $this->commissionedId) {
            return [];
        }

        if ($this->split['splitTypePerson'] === 'AFFILIATE' && filled($this->order->affiliate)) {
            $this->split['type_commission']  = $this->order->affiliate->type;
            $this->split['value_commission'] = $this->order->affiliate->value;
        } else if ($this->split['splitTypePerson'] === 'CO_PRODUCER') {
            $this->split['type_commission'] = 'percentage';

            $percentageCommissionCoproducerShopOrder = $this->order->item->product->parentProduct
                ->coproducers()
                ->withTrashed()
                ->whereRelation('user.shopUser', 'username_banking', $this->split['username'])
                ->value('percentage_commission');

            $this->split['value_commission'] = $percentageCommissionCoproducerShopOrder;
        } else {
            return [];
        }

        $commissioningOrder = $this->order->commissions()->updateOrCreate(
            [
                'commissioned_id' => $this->commissionedId,
                'type'            => $this->split['splitTypePerson'],
                'order_id'        => $this->order->id,
                'type_commission' => $this->split['type_commission'],
            ],
            [
                'value'            => $this->split['valueSplit'],
                'value_commission' => $this->split['value_commission'],
            ]
        );

        $commissioningOrder->attributes->set($this->split);
        $commissioningOrder->save();

        return $commissioningOrder->toArray();
    }

}
