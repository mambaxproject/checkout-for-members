<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\CloudFlare\CloudflareStreamService;
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\Facades\Log;

class CloudflareStreamController extends Controller
{
    public function __construct(
        private readonly CloudflareStreamService $cloudflareService
    ) {}

    public function getUploadTusUrl(Request $request): JsonResponse
    {
        try {
            $metadata = $request->input('metadata', []);
            $uploadData = $this->cloudflareService->createTusUpload($metadata);
            return response()->json($uploadData);
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao obter URL de upload direto do CloudFlare.',
                [
                    'error'    => $th->getMessage(),
                    'metadata' => $request->input('metadata', []),
                    'trace' => $th->getTraceAsString()
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkVideoStatus(string $videoId): JsonResponse
    {
        try {
            $status = $this->cloudflareService->getVideoStatus($videoId);
            return response()->json($status);
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao verificar status do vídeo no CloudFlare.',
                [
                    'error'  => $th->getMessage(),
                    'video_id' => $videoId,
                    'trace' => $th->getTraceAsString()
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar status do vídeo',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getVideoDetails(string $videoId): JsonResponse
    {
        try {
            $details = $this->cloudflareService->getVideoDetails($videoId);
            return response()->json($details);
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao obter detalhes do vídeo no CloudFlare.',
                [
                    'error'    => $th->getMessage(),
                    'video_id' => $videoId,
                    'trace' => $th->getTraceAsString()
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter detalhes do vídeo',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteVideo(string $videoId): JsonResponse
    {
        try {
            $result = $this->cloudflareService->deleteVideo($videoId);

            return response()->json($result);
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao deletar vídeo no CloudFlare.',
                [
                    'error'    => $th->getMessage(),
                    'video_id' => $videoId,
                    'trace' => $th->getTraceAsString()
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar vídeo',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
