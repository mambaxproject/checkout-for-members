<?php

namespace App\Services\Notification;

use App\Enums\CustomNotificationTypeEnum;
use App\Helpers\PhoneHelper;
use App\Models\CustomNotification;
use App\Models\NotificationAction;
use App\Models\Product;
use App\Models\User;
use App\Repositories\NotificationActionRepository;
use App\Repositories\CustomNotificationRepository;
use App\Repositories\ProductRepository;
use App\Services\Notification\NotificationApiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NotificationService
{
    protected NotificationActionRepository $notificationActionRepository;
    protected CustomNotificationRepository $notificationRepository;

    public function __construct()
    {
        $this->notificationActionRepository = new NotificationActionRepository(new NotificationAction);
        $this->notificationRepository = new CustomNotificationRepository(new CustomNotification());
    }

    public function index(array $requestData, string $services): View
    {
        $user = Auth::user();
        $whatsappConnection = $this->getWhatsappConnection($services, $user);
        $actions = $this->notificationActionRepository->getByUserId($user->id, $requestData);
        return view('dashboard.notification.index', compact('services', 'whatsappConnection', 'actions'));
    }

    private function getWhatsappConnection(string $serviceName, User $user): array
    {
        if ($serviceName != CustomNotificationTypeEnum::WHATSAPP->value) {
            return ['connection' => false];
        }

        $route = 'instances/' . $user->id . config('services.messageBroker.key');

        $response = (new NotificationApiService())->get($route)['data'];

        if ($response['status']) {
            return [
                'connection' => true,
                'phoneConnected' => PhoneHelper::getPhoneTreated($response['phoneConnected'])
            ];
        }

        return [
            'connection' => false,
            'qrCode' => $response['qrCode']
        ];
    }

    public function connectWhatsapp(): array
    {
        $user = Auth::user();
        return $this->getWhatsappConnection('whatsapp', $user);
    }

    public function getProductsAvailable(): array
    {
        $user = Auth::user();
        return (new ProductRepository(new Product()))->getAvailableCreateActionByUserId($user->id)
            ->toArray();
    }
    public function create(array $requestData): void
    {
        DB::beginTransaction();
        try {
            $action = $this->notificationActionRepository->create($requestData['action']);
            $this->createEachNotification($requestData['notifications'], $action->id);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::channel('notification')->error(
                'Erro ao criar notification action.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'ActionService.create',
                    'body' => $requestData
                ]
            );
            throw $th;
        }
    }

    private function createEachNotification(array &$notifications, int $actionId): void
    {
        $dataNotifications = array_map(function ($notification) use ($actionId) {
            $notification['action_id'] = $actionId;
            $notification['url_embed'] = $this->checkNeedsaveImageOnBucket($notification);
            return $notification;
        }, $notifications);
        $this->notificationRepository->insert($dataNotifications);
    }

    private function checkNeedsaveImageOnBucket(array &$notification): string|null
    {
        if (is_null($notification['url_embed'])) {
            return null;
        }

        return $this->saveArchiveStorage($notification['url_embed']);
    }

    public function edit(array $requestData): NotificationAction
    {
        $actionId = $requestData['actionId'];
        $action = $this->notificationActionRepository->findByIdWithProduct($actionId);
        $action->notifications = $this->notificationRepository->getByActionId($actionId);
        return $action;
    }

    public function update(array $requestData): void
    {
        DB::beginTransaction();
        try {
            $this->updateAction($requestData['action']);
            $this->updateNotifications($requestData['notifications']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::channel('notification')->error(
                'Erro ao atualizar notification action.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'ActionService.update',
                    'body' => $requestData
                ]
            );
            throw $th;
        }
    }

    private function updateAction(array $notificationAction): void
    {
        $actionId = array_key_first($notificationAction);
        $this->notificationActionRepository->update($notificationAction[$actionId], $actionId);
    }

    private function updateNotifications(array $notifications): void
    {
        foreach ($notifications as $notification) {
            $notificationId = array_key_first($notification);
            $notification[$notificationId]['url_embed'] = $this->checkNeedUpdateImageOnBucket($notification[$notificationId], $notificationId);
            unset($notification[$notificationId]['oldImage']);
            $this->notificationRepository->update($notification[$notificationId], $notificationId);
        }
    }

    private function checkNeedUpdateImageOnBucket(array $notification, $id): string|null
    {
        if (!array_key_exists('url_embed', $notification)) {
            return null;
        }

        if (!$notification['url_embed'] instanceof UploadedFile) {
            return $notification['url_embed'];
        }

        $this->checkNeedDeleteImage($notification);

        return $this->saveArchiveStorage($notification['url_embed']);
    }

    private function checkNeedDeleteImage(array $notification): void
    {
        if (!array_key_exists('oldImage', $notification)) {
            return;
        }

        if (Storage::disk('s3')->exists('/' . $notification['oldImage'])) {
            Storage::disk('s3')->delete('/' . $notification['oldImage']);
        }
    }

    private function saveArchiveStorage(UploadedFile $archive): string
    {
        $pathName = 'uploads/notifications' . uniqid() . '.' . $archive->getClientOriginalExtension();
        $response = Storage::disk('s3')->put($pathName, fopen($archive->getRealPath(), 'r+'));

        if ($response) {
            return $pathName;
        };

        throw new \Exception('Falha ao armazenar arquivo no s3');
    }

    public function changeStatus(int $actionId): void
    {
        $action = $this->notificationActionRepository->findById($actionId);
        $this->checkProductIsActive($action);
        $action->status = !$action->status;
        $action->save();
    }

    private function checkProductIsActive(NotificationAction $action): void
    {
        $product = Product::where('id', $action->product_id)->exists();
        if (!$product) {
            throw new \Exception('produto desativado');
        }
    }

    public function duplicateAction(array $requestData): void
    {
        DB::beginTransaction();
        try {
            $action = $this->notificationActionRepository->create($requestData['action']);
            $this->duplicateNotifications($requestData['duplicateActionId'], $action->id);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::channel('notification')->error(
                'Erro ao duplicar notification action.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'ActionService.update',
                    'body' => $requestData
                ]
            );
            throw $th;
        }
    }

    private function duplicateNotifications(int $duplicateActionId, int $actionId): void
    {
        $notificationsToDuplicate = $this->notificationRepository->getByActionId($duplicateActionId);

        foreach ($notificationsToDuplicate as  $notification) {
            $dataToInsert = $this->getNotificationTreatedToDuplicate($notification, $actionId);
            $this->notificationRepository->insert($dataToInsert);
        }
    }

    private function getNotificationTreatedToDuplicate(CustomNotification $notification, int $actionId): array
    {
        return [
            'type_id' => $notification->type_id,
            'action_id' => $actionId,
            'event_id' => $notification->event_id,
            'text_whatsapp' => $notification->text_whatsapp,
            'dispatch_time' => $notification->dispatch_time,
            'url_embed' => $notification->url_embed,
            'status' => $notification->status,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function disconnectWhatsapp(): void
    {
        $user = Auth::user();
        $route = 'instances/' . $user->id . config('services.messageBroker.key');
        (new NotificationApiService())->delete($route);
    }
}
