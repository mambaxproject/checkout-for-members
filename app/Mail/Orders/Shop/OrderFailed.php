<?php

namespace App\Mail\Orders\Shop;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class OrderFailed extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Order $order
    ) { }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Verifique o que aconteceu.',
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
            subject: 'Oops! A compra do seu cliente não foi aprovada! ❌',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.shop.orderFailed',
            with: [
                'order' => $this->order,
            ],
        );
    }

}
