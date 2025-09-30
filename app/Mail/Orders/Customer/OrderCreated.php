<?php

namespace App\Mail\Orders\Customer;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Address, Attachment, Content, Envelope};
use Illuminate\Queue\SerializesModels;

class OrderCreated extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            replyTo: [
                new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            ],
            subject: 'Recebemos seu pedido do produto ' . $this->order->items->implode('product.parentProduct.name')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.customer.orderCreated',
            with: [
                'order' => $this->order,
            ],
        );
    }

    public function attachments(): array
    {
        $data = [];

        if ($this->order->payment->isBillet) {
            $contents = file_get_contents($this->order->payment->external_url);
            $data     = [Attachment::fromData(fn () => $contents, 'Boleto.pdf')];
        }

        return $data;
    }

}
