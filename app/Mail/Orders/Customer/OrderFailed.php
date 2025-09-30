<?php

namespace App\Mail\Orders\Customer;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Address, Content, Envelope, Headers};
use Illuminate\Queue\SerializesModels;

class OrderFailed extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(
        public Order $order
    ) {}

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
            subject: 'Oops! A compra não foi aprovada.',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.customer.orderFailed',
            with: [
                'order' => $this->order,
            ],
        );
    }

}
