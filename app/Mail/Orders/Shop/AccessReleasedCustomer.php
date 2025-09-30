<?php

namespace App\Mail\Orders\Shop;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class AccessReleasedCustomer extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Order $order,
    ) { }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'O acesso ao conteúdo exclusivo está liberado.',
            ]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            replyTo: [
                new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            ],
            subject: 'Compra confirmada! Seu cliente já tem acesso ao seu produto. 🤝',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.shop.accessReleasedCustomer',
            with: [
                'order' => $this->order,
            ],
        );
    }

}
