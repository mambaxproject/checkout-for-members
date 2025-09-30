<?php

namespace App\Mail\Telegram;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Address, Content, Envelope};
use Illuminate\Queue\SerializesModels;

class TelegramInviteLink extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            replyTo: [
                new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            ],
            subject: 'Seu link de acesso ao grupo do Telegram'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.telegram.customer.groupInviteLink',
            with: [
                'order'      => $this->order,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

}
