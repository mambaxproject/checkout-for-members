<?php

namespace App\Mail\Coproducers;

use App\Models\Coproducer;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class AcceptedCoproducerShop extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(
        public Coproducer $coproducer,
        public Product $product,
    ) {}

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Coprodutor aceitou'
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
            subject: 'Coprodutor aceitou',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.coproducers.acceptedCoproducerShop',
            with: [
                'product'    => $this->product,
                'coproducer' => $this->coproducer,
            ],
        );
    }
}
