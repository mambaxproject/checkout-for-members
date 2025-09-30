<?php

namespace App\Mail\Subscriptions\Customer;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Address, Content, Envelope};
use Illuminate\Queue\SerializesModels;

class UpdateOfferSubscription extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Order $order,
        public string $offer_id,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            replyTo: [
                new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            ],
            subject: 'Atualize sua assinatura do produto ' . $this->order->item->product->parentProduct->name,
        );
    }

    public function content(): Content
    {
        $linkCustomerUpdateSubscription = app('url')->signedRoute(
            'public.subscription.editOffer',
            [
                'order_hash' => $this->order->order_hash,
                'product'    => $this->offer_id,
            ],
            now()->addHours(24),
        );

        return new Content(
            view: 'emails.subscriptions.customer.updateOfferSubscription',
            with: [
                'order'                          => $this->order,
                'linkCustomerUpdateSubscription' => $linkCustomerUpdateSubscription,
            ],
        );
    }

}
