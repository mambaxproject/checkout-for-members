<?php

namespace App\Mail\AbandonedCarts\Customer;

use App\Models\AbandonedCart;
use App\Models\Product;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AbandonedCartCreated extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(
        public object $abandonedCart,
        public Product $product
    ) {}

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Finalize sua compra!',
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
            subject: 'Finalize sua compra!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.abandonedCarts.customer.abandonedCartCreated',
            with: [
                'abandonedCart' => $this->abandonedCart,
                'product' => $this->product
            ],
        );
    }
}
