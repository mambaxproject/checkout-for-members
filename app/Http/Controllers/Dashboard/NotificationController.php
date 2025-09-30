<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\CreateNotificationActionRequest;
use App\Http\Requests\Notification\DuplicateNotificationActionRequest;
use App\Http\Requests\Notification\EditNotificationActionRequest;
use App\Http\Requests\Notification\GetNotificationActionRequest;
use App\Http\Requests\Notification\StoreNotificationActionRequest;
use App\Http\Requests\Notification\UpdateNotificationActionRequest;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    public function index(GetNotificationActionRequest $request, string $services): View
    {
        return $this->notificationService->index($request->toArray(), $services);
    }

    public function connectWhatsapp(): JsonResponse
    {
        $data = $this->notificationService->connectWhatsapp();
        return response()->json($data);
    }

    public function store(StoreNotificationActionRequest $request): View
    {
        $data = $request->toArray();
        return view('dashboard.notification.create', compact('data'));
    }

    public function getProductsAvailable(): JsonResponse
    {
        $data = $this->notificationService->getProductsAvailable();
        return response()->json($data);
    }

    public function create(CreateNotificationActionRequest $request): RedirectResponse
    {
        try {
            $this->notificationService->create($request->toArray());
            return redirect()->route('dashboard.notification.index', ['services' => 'whatsapp'])
                ->with('message',  'Ação criada com sucesso!');
        } catch (\Throwable $th) {
            return back()->withErrors(['message' => 'Ocorreu um erro ao criar a ação.']);
        }
    }

    public function edit(EditNotificationActionRequest $request): View
    {
        $noticationAction = $this->notificationService->edit($request->toArray());
        return view('dashboard.notification.edit', compact('noticationAction'));
    }

    public function update(UpdateNotificationActionRequest $request): RedirectResponse
    {
        try {
            $this->notificationService->update($request->toArray());
            return redirect()->route('dashboard.notification.index', ['services' => 'whatsapp'])
                ->with('message',  'Ação atualizada com sucesso!');
        } catch (\Throwable $th) {
            return back()->withErrors(['message' => 'Ocorreu um erro ao criar ao atualizar ação.']);
        }
    }

    public function changeStatus(int $actionId): RedirectResponse
    {
        try {
            $this->notificationService->changeStatus($actionId);
            return back()->with('message',  'Ação atualizada com sucesso!');
        } catch (\Throwable $th) {
            return back()->with('error',  'Este produto foi removido!');
        }
    }

    public function duplicate(DuplicateNotificationActionRequest $request): RedirectResponse
    {
        try {
            $this->notificationService->duplicateAction($request->toArray());
            return redirect()->route('dashboard.notification.index', ['services' => 'whatsapp'])
                ->with('message',  'Ação duplicada com sucesso!');
        } catch (\Throwable $th) {
            return back()->withErrors(['message' => 'Ocorreu um erro ao criar ao duplicar ação.']);
        }
    }

    public function disconnectWhatsapp(): void
    {
       $this->notificationService->disconnectWhatsapp();
    }
}
