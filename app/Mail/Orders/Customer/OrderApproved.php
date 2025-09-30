<?php

namespace App\Mail\Orders\Customer;

use App\Models\{Order, Product};
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Address, Content, Envelope};
use Illuminate\Queue\SerializesModels;

class OrderApproved extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Order $order,
        public ?Product $productItemOrder = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            replyTo: [
                new Address(config('services.mail.from.address'), config('services.mail.from.name')),
            ],
            subject: 'Pagamento aprovado'
        );
    }

    public function content(): Content
    {
        $product = $this->productItemOrder
            ? $this->productItemOrder->parentProduct
            : $this->order->items->first()->product->parentProduct;

        $attachmentProduct = $product->getMedia('attachment');

        $linkAccess = ($attachmentProduct->isNotEmpty() && boolval($product->getValueSchemalessAttributes('allowAttachment')))
            ? $attachmentProduct->last()->getUrl()
            : '';

        return new Content(
            view: 'emails.orders.customer.orderApproved',
            with: [
                'order'      => $this->order,
                'linkAccess' => $linkAccess,
                'product'    => $product,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

}
