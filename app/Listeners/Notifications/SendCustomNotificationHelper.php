<?php

namespace App\Listeners\Notifications;

use App\Helpers\StorageUrl;
use App\Models\CustomNotification;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\CustomNotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\Notification\NotificationApiService;
use Illuminate\Support\Facades\Log;

trait SendCustomNotificationHelper
{
    private function generalProcess(): void
    {
        $order = (new OrderRepository(new Order()))->getOrdersGeneralNotificable($this->orderId);

        $notification = (new CustomNotificationRepository(new CustomNotification()))->getByEventIdAndParentId($this->eventTypeId, $order->parentId)
            ->first();

        $product = (new ProductRepository(new Product()))->getById($order->parentId);

        if (is_null($notification)) {
            return;
        }

        if (is_null($notification->url_embed) && is_null($notification->text_whatsapp)) {
            return;
        }

        $arrayDataTreated = $this->getDataTreat($notification, $order, $product);
        $this->sendData($notification->user_id, $arrayDataTreated);
    }

    private function getDataTreat(CustomNotification $notification, Order $order, Product $product): array
    {
        $dataToGetString = $this->treatDataNotification($order, $product);
        $textTreated = $this->replacePlaceHolders($notification->text_whatsapp ?? '', $dataToGetString);
        $urlEmbed = !is_null($notification->url_embed) ? StorageUrl::getStorageUrlS3() . $notification->url_embed : null;
        return array_filter([
            'phoneNumber' => '55' .  preg_replace('/\D/', '', $order->phoneNumber),
            'text' => $textTreated,
            'scheduledTimeMinute' => $notification->dispatch_time,
            'url_embed' => $urlEmbed,
            'type' => 'whatsapp'
        ], fn($value) => $value !== null && $value !== '');
    }

    private function treatDataNotification(Order $order, Product $product): array
    {
        $name = explode(' ', $order->nameClient);

        $firstName = $name[0];
        $lastName = end($name);
        return [
            'nome_cliente' => ucfirst($firstName),
            'sobrenome_cliente' => ucfirst($lastName) ?? 'Sobrenome não encontrado',
            'nome_produto' =>  $product->name,
            'email_cliente' => $order->emailClient,
            'link_checkout' => config('app.url') . '/' .  $order->linkProduct
        ];
    }

    private function replacePlaceHolders(string $text, array $data): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace('{' . $key . '}', $value, $text);
        }

        return $text;
    }

    private function sendData(int $userId, array &$data): void
    {
        $route = 'message/' . $userId . config('services.messageBroker.key');

        try {
            retry(3, function () use ($route, $data) {
                (new NotificationApiService())->post($route, $data);
            }, 2000);
        } catch (\Throwable $e) {
            Log::channel('notification')->error('Falha ao enviar notificação após retries', [
                'error' => $e->getMessage(),
                'userId' => $userId,
                'route' => $route,
            ]);
        }
    }
}
