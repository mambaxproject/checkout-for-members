<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationApiService
{
    private $urlBase, $client, $token;

    public function __construct(int $timeout = 30)
    {
        $this->urlBase = config('services.messageBroker.url');
        $this->client = Http::accept('application/json')
            ->timeout($timeout);
        $this->token = $this->authApi();
    }

    public function authApi(): string
    {
        $url = $this->setEndpointUrl('auth');
        $body = ['token' => config('services.messageBroker.token')];
        return Http::asForm()->post($url, data: $body)->json()['data']['token'];
    }

    public function post(string $route, array $body): mixed
    {
        try {
            $response = $this->client->withToken($this->token)
                ->post($this->setEndpointUrl($route), $body);

            return $response->successful()
                ? $response->json()
                : throw new \Exception($response->body());
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao enviar para api de notificação.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'NotificationApiService.post',
                    'route' => $route,
                    'body' => $body
                ]
            );
            throw $th;
        }
    }

    public function get(string $route, $params = []): mixed
    {
        try {
            $response = $this->client->withToken($this->token)
                ->get($this->setEndpointUrl($route), $params);

            return $response->successful()
                ? $response->json()
                : throw new \Exception($response->body());
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao buscar para api notificação.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'NotificationApiService.get',
                    'route' => $route,
                    'params' => $params
                ]
            );
            throw $th;
        }
    }

    public function delete(string $route, $params = []): mixed
    {
        try {
            $response = $this->client->withToken($this->token)
                ->delete($this->setEndpointUrl($route), $params);

            return $response->successful()
                ? $response->json()
                : throw new \Exception($response->body());
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao deletar na api notificação.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'NotificationApiService.delete',
                    'route' => $route,
                    'params' => $params
                ]
            );
            throw $th;
        }
    }

    private function setEndpointUrl(string $route): string
    {
        return $this->urlBase . $route;
    }
}
