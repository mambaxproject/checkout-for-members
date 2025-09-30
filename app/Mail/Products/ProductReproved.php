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

class ProductReproved extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(
        public Product $product,
    ) { }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Não se preocupe, pequenos detalhes fazem toda a diferença! ',
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
            subject: 'Seu produto requer ajustes antes de ser publicado.',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.products.productReproved',
            with: [
                'product' => $this->product,
            ],
        );
    }

}
