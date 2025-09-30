<?php

namespace App\Mail\Affiliates;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderApprovedAffiliate extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Order $order
    ) { }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            replyTo: [
                new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            ],
            subject: 'Você tem uma nova venda no evento '. $this->order->items->implode('product.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliates.orderApprovedAffiliate',
            with: [
                'order' => $this->order,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

}
