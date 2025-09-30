<?php

namespace App\Mail\Products;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class ProductPublished extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(
        public Product $product,
    ) { }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Preparado para suas vendas?',
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
            subject: 'Tudo certo! Seu produto já está disponível para compra. 💸',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.products.productPublished',
            with: [
                'product' => $this->product,
            ],
        );
    }

}
