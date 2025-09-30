<?php

namespace App\Listeners\Notifications;

use App\Events\AbandonedCartNotification;
use App\Helpers\StorageUrl;
use App\Models\AbandonedCart;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\Notification\NotificationApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AbandonedCartCustomNotification implements ShouldQueue
{

    public $connection = 'redis';
    public $queue = 'notifications';

    use SendCustomNotificationHelper;

    public function handle(AbandonedCartNotification $event): void
    {
        $abandonedCart = $event->abandonedCart;
        $abandonedCart->link_checkout = $this->setParamsAbandonedCartWhatsapp($abandonedCart);

        if (is_null($abandonedCart->custom_notification_id)) {
            return;
        }

        if ($this->checkAlreadySend($abandonedCart)) {
            return;
        }

        try {
            $originalProduct = (new ProductRepository(new Product()))
                ->getById($abandonedCart->parent_id);
            $dataToSend = $this->getDataTreatAbandonedCart($abandonedCart, $originalProduct);
            $userNotificationId = $originalProduct->shop->owner_id;
            $this->sendData($userNotificationId, $dataToSend);
            AbandonedCart::where('id', $abandonedCart->id)->update(
                [
                    'whatsapp_notification_sent' => true,
                    'updated_at' => now()
                ]
            );
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao criar notificação.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'AbandonedCartCustomNotification.handle',
                    'body' => ['Id do carrinho abandonado: ' => $abandonedCart->id]
                ]
            );
        }
    }

    private function setParamsAbandonedCartWhatsapp(object $abandonedCart): string
    {
        return $abandonedCart->link_checkout . '?abId=' . $abandonedCart->id .
            '&utm_source=abandoned_cart&utm_campaign=abandoned_cart&utm_medium=whatsapp';
    }

    private function getDataTreatAbandonedCart(object $abandonedCart, Product $originalProduct): array
    {
        $dataToGetString = $this->treatDataAbandonedCart($abandonedCart, $originalProduct);
        $textTreated = $this->replacePlaceHolders($abandonedCart->text_whatsapp ?? '', $dataToGetString);
        $urlEmbed = !is_null($abandonedCart->url_embed) ? StorageUrl::getStorageUrlS3() . $abandonedCart->url_embed : null;
        return array_filter([
            'phoneNumber' => '55' . preg_replace('/\D/', '', $abandonedCart->phone_number),
            'text' => $textTreated,
            'scheduledTimeMinute' => 0,
            'url_embed' => $urlEmbed,
            'type' => 'whatsapp'
        ], fn($value) => $value !== null && $value !== '');
    }

    private function treatDataAbandonedCart(object $abandonedCart, Product $originalProduct): array
    {
        $name = explode(' ', $abandonedCart->name);
        $firstName = $name[0];
        $lastName = end($name);
        return [
            'nome_cliente' => ucfirst($firstName),
            'sobrenome_cliente' => ucfirst($lastName) ?? 'Sobrenome não encontrado',
            'nome_produto' =>  $originalProduct->name,
            'email_cliente' => $abandonedCart->email,
            'link_checkout' => $abandonedCart->link_checkout,
            'cupom' => $abandonedCart->code ?? '*Cupom expirado ou inativo*',
            'validade_cupom' =>  $abandonedCart->end_at ?
                \Carbon\Carbon::parse($abandonedCart->end_at)->format('d/m/Y H:i')
                : '*Cupom expirado ou inativo*'

        ];
    }

    private function checkAlreadySend(object $abandonedCart): bool
    {
        $abandonedCartNotification = AbandonedCart::where('id', $abandonedCart->id)
            ->select('whatsapp_notification_sent')->first();
        return $abandonedCartNotification->whatsapp_notification_sent;
    }
}
