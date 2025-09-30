<?php

namespace App\Mail\Affiliates;

use App\Models\Affiliate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class AcceptedAffiliateShop extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(
        public Affiliate $affiliate,
    ) {}

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'confirmação afiliado shop'
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
            subject: "confirmação afiliado shop",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliates.acceptedAffiliateShop',
            with: [
                'affiliate' => $this->affiliate,
            ],
        );
    }
}
