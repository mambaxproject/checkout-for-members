<?php

namespace App\Services\CloudFlare;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{Http, Log};

class CloudflareStreamService
{
    private readonly string $apiToken;

    private readonly string $accountId;

    private readonly string $baseUrl;

    public function __construct()
    {
        $this->apiToken  = config('services.cloudflare.stream.api_token');
        $this->accountId = config('services.cloudflare.stream.account_id');
        $this->baseUrl   = config('services.cloudflare.base_url', 'https://api.cloudflare.com/client/v4/');
    }

    public function createTusUpload(array $metadata = []): array
    {
        try {
            $url = $this->baseUrl . "accounts/{$this->accountId}/stream";

            $headers = [
                'Authorization'   => 'Bearer ' . $this->apiToken,
                'Tus-Resumable'   => '1.0.0',
                'Upload-Length'   => $metadata['size'] ?? 0,
                'Upload-Metadata' => $this->buildTusMetadataArray($metadata),
            ];

            Log::info('Tentando criar sessão TUS', [
                'url' => $url,
                'headers' => $headers,
                'metadata' => $metadata,
            ]);

            $response = Http::withHeaders($headers)->timeout(300)->post($url);

            Log::info('Resposta da criação da sessão TUS', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
            ]);
            
            if (!$response->successful()) {
                throw new \Exception($response);
            }

            $location = $response->header('Location');
            $cleanUrl = strtok($location, '?');
            $videoId = $this->extractVideoIdFromLocation($cleanUrl);

            Log::info('TUS upload session criada', [
                'video_id' => $videoId,
                'upload_url' => $cleanUrl,
            ]);

            return [
                'success' => true,
                'upload_url' => $cleanUrl,
                'video_id' => $videoId,
            ];
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao criar sessão TUS upload.',
                [
                    'error' => $th->getMessage(),
                    'metadata' => $metadata,
                    'trace' => $th->getTraceAsString()
                ]
            );
            throw $th;
        }
    }

    private function buildTusMetadataArray(array $metadata = []): string
    {
        $tusMetadata = [];
        $metadataString = '';

        if (array_key_exists('size', $metadata)) {
            unset($metadata['size']);
        }

        foreach ($metadata as $key => $value) {
            $tusMetadata[$key] = base64_encode($value);
        }

        foreach ($tusMetadata as $key => $value) {
            $metadataString .= "{$key} {$value},";
        }

        return rtrim($metadataString, ',');
    }

    private function extractVideoIdFromLocation(string $location): string
    {
        $parts = explode('/', $location);

        return end($parts);
    }

    public function getVideoDetails(string $videoId): array
    {
        $url = $this->baseUrl . "accounts/{$this->accountId}/stream/{$videoId}";

        $response = Http::withToken($this->apiToken)->get($url);

        if ($response->successful()) {
            return [
                'success' => true,
                'data'    => $response->json()['result'],
            ];
        }

        return [
            'success' => false,
            'message' => $response->body(),
        ];
    }

    public function getVideoStatus(string $videoId): array
    {
        $details = $this->getVideoDetails($videoId);

        if (! $details['success']) {
            return $details;
        }

        $video = $details['data'];

        return [
            'success'       => true,
            'status'        => $video['status']['state'] ?? 'unknown',
            'ready'         => ($video['status']['state'] ?? '') === 'ready',
            'preview_url'   => $video['preview'] ?? null,
            'thumbnail_url' => $video['thumbnail'] ?? null,
            'playback_url'  => $video['playback']['hls'] ?? null,
        ];
    }

    public function deleteVideo(string $videoId): array
    {
        $url = $this->baseUrl . "accounts/{$this->accountId}/stream/{$videoId}";

        $response = Http::withToken($this->apiToken)->delete($url);

        return [
            'success' => $response->successful(),
            'message' => $response->successful() ? 'Video deleted successfully' : $response->body(),
        ];
    }
}
