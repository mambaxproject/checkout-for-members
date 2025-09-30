<?php

namespace App\Mail\Orders\Customer;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderRecurringApproved extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Order $order,
        public Product $product
    ) {}

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'O acesso ao conteúdo foi mantido.',
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
            subject: 'O Pagamento da sua assinatura foi renovado 🤝',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.customer.orderRecurringApproved',
            with: [
                'order' => $this->order,
                'product' => $this->product
            ],
        );
    }
}
