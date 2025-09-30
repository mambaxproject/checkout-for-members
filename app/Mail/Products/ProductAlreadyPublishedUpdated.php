<?php

namespace App\Mail\Products;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Address, Content, Envelope, Headers};
use Illuminate\Queue\SerializesModels;

class ProductAlreadyPublishedUpdated extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(
        public Product $product,
    ) {}

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Cada ajuste é uma oportunidade de oferecer algo ainda melhor aos seus clientes.',
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
            subject: 'Atualizações salvas com sucesso! ✅',
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
