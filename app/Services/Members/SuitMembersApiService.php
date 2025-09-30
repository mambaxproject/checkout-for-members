<?php

namespace App\Services\Members;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SuitMembersApiService
{
    private $urlBase, $client, $token;

    public function __construct(int $timeout = 30, string $token = '', string $type = 'producer')
    {
        $this->urlBase = config('services.members.url');
        $this->client = Http::accept('application/json')
            ->timeout($timeout);
        $this->token = $this->authApi($token, $type);
    }

    public function authApi(string $token, string $type): string
    {
        $route = $type == 'producer' ? 'producer/auth' : 'admin/auth';
        $url = $this->setEndpointUrl($route);
        $body = ['token' => $token];
        return Http::asForm()->post($url, data: $body)->json()['data']['token'];
    }

    public function post(string $route, array $body): mixed
    {
        try {
            $response = $this->client->withToken($this->token)
                ->post($this->setEndpointUrl($route), $body);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception($response->body());
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao enviar para api de membros.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'SuitMembersApiService.post',
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

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception($response->body());
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao buscar para api membros.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'SuitMembersApiService.get',
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

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception($response->body());
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao deletar na api membros.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'SuitMembersApiService.delete',
                    'route' => $route,
                    'params' => $params
                ]
            );
            throw $th;
        }
    }

    public function put(string $route, array $body = []): mixed
    {
        try {
            $response = $this->client->withToken($this->token)
                ->put($this->setEndpointUrl($route), $body);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception($response->body());
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao atualizar na api de membros.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'SuitMembersApiService.put',
                    'route' => $route,
                    'body' => $body
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
