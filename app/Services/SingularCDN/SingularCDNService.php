<?php

namespace App\Services\SingularCDN;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SingularCDNService
{
    public PendingRequest $api;

    public readonly string $chaveSingularCdn;
    public function __construct()
    {
        $this->api = Http::withHeaders(['Authorization' => 'Token ' . config('services.singularcdn.api_token')])
            ->asForm()
            ->baseUrl(config('services.singularcdn.base_url'));

        $this->api->acceptJson();

        $this->chaveSingularCdn = config('services.singularcdn.chave');
    }

    public function addDomain(string $domain): array
    {
        $rootDomain = str_ireplace('www.', '', parse_url($domain)['host']);
        $url        = 'seguro.' . $rootDomain;

        return $this->api->post('/v1.0/servico/' . $this->chaveSingularCdn . '/dominio', [
            'dominio'     => $url,
            'host_padrao' => $url,
            'backend'     => config('services.singularcdn.backend'),
            'normalizar'  => 'sim',
        ])->json();
    }

    public function apply(): array
    {
        return $this->api
            ->get('/v1.0/servico/' . $this->chaveSingularCdn . '/aplicar')
            ->json();
    }

    public function deleteDomain(int $id): array
    {
        return $this->api
            ->delete('/v1.0/servico/' . $this->chaveSingularCdn . '/dominio/' . $id)
            ->json();
    }

}
