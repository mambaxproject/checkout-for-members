<?php

namespace App\Mail\Affiliates;

use App\Models\Affiliate;
use App\Models\Product;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class NewAffiliate extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Affiliate $affiliate,
        public Product $product,
    ) { }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Torne-se um parceiro do ' . $this->product->name,
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
            subject: "📨 Você foi convidado a se tornar afiliado. Junte-se agora!",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliates.newAffiliate',
            with: [
                'product'   => $this->product,
                'affiliate' => $this->affiliate,
            ],
        );
    }

}