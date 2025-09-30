<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Storage;

class FileUploadChunckingController extends Controller
{
    public function uploadChunk(Request $request): JsonResponse
    {
        $file        = $request->file('file');
        $uuid        = $request->input('dzUuid');
        $index       = $request->input('dzchunkindex');
        $totalChunks = $request->input('dztotalchunkcount');

        $chunkPath = storage_path("app/chunks/{$uuid}");

        if (! file_exists($chunkPath)) {
            mkdir($chunkPath, 0777, true);
        }

        $file->move($chunkPath, "chunk_{$index}");

        if ((int) $index + 1 == (int) $totalChunks) {
            $uploadsPath = storage_path('app/uploads');

            if (! file_exists($uploadsPath)) {
                mkdir($uploadsPath, 0777, true);
            }

            $finalPath = storage_path("app/uploads/{$uuid}_{$file->getClientOriginalName()}");
            $output    = fopen($finalPath, 'ab');

            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkFile = "{$chunkPath}/chunk_{$i}";
                $in        = fopen($chunkFile, 'rb');
                stream_copy_to_stream($in, $output);
                fclose($in);
                unlink($chunkFile);
            }

            fclose($output);
            Storage::deleteDirectory("chunks/{$uuid}");
        }

        return response()->json([
            'success' => true,
            'path'    => "uploads/{$uuid}_{$file->getClientOriginalName()}",
            'message' => 'Upload completo',
        ]);
    }

}
