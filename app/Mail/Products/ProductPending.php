<?php

namespace App\Mail\Products;

use App\Models\Product;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class ProductPending extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Product $product,
    ) { }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Aguardando aprovação!!',
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
            subject: 'Quase lá! Seu produto está pronto para o lançamento. 🚀',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.products.productPending',
            with: [
                'product' => $this->product,
            ],
        );
    }

}
