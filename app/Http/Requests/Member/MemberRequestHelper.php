<?php

namespace App\Http\Requests\Member;

use Illuminate\Support\Facades\Storage;
use Obs\ObsClient;

trait MemberRequestHelper
{
    private function getVideoType(string $videoUrl): string
    {
        $youtubePattern = '/^(https?:\/\/)?(www\.)?(youtube|youtu|youtube-nocookie)\.(com|be)\/.+$/';
        $vimeoPattern = '/^https?:\/\/(www\.)?vimeo\.com\/\d+(\/[\w-]+)?(\?.*)?$/';
        $pandaPattern = '/^https?:\/\/player-vz-[\w-]+\.tv\.pandavideo\.com\.br\/embed\/\?v=[\w-]{36}$/';

        if (preg_match($youtubePattern, $videoUrl)) {
            return 'youtube';
        }

        if (preg_match($vimeoPattern, $videoUrl)) {
            return 'vimeo';
        }

        if (preg_match($pandaPattern, $videoUrl)) {
            return 'panda';
        }

        return 'unknown';
    }

    private function isValidVideoUrl(string $url): bool
    {
        $youtubePattern = '/^(https?:\/\/)?(www\.)?(youtube|youtu|youtube-nocookie)\.(com|be)\/.+$/';
        $vimeoPattern = '/^https?:\/\/(www\.)?vimeo\.com\/\d+(\/[\w-]+)?(\?.*)?$/';
        $pandaPattern = '/^https?:\/\/player-vz-[\w-]+\.tv\.pandavideo\.com\.br\/embed\/\?v=[\w-]{36}$/';

        return preg_match($youtubePattern, $url)
            || preg_match($vimeoPattern, $url)
            || preg_match($pandaPattern, $url);
    }

    private function removeUnchangedFields(array $data): array
    {
        foreach ($data as $key => $value) {
            $oldKey = 'old_' . $key;
            if ($this->has($oldKey) && $this->get($oldKey) === $value) {
                unset($data[$key]);
            }
        }

        return array_filter($data, function ($value) {
            return !is_null($value) && $value !== '';
        });
    }

    private function saveS3(mixed $image): string
    {
        if (is_null($image)) {
            return '';
        }

        $bucket   = config('filesystems.disks.s3.bucket');
        $accessKey = config('filesystems.disks.s3.key');
        $secretKey = config('filesystems.disks.s3.secret');
        $endpoint  = config('filesystems.disks.s3.endpoint');

        $pathName = 'uploads/members/' . uniqid() . '.' . $image->getClientOriginalExtension();

        $obsClient = new ObsClient([
            'key' => $accessKey,
            'secret' => $secretKey,
            'endpoint' => $endpoint,
        ]);

        $obsClient->putObject([
            'Bucket' => $bucket,
            'Key' => $pathName,
            'SourceFile' => $image->getRealPath(),
            'ContentDisposition' => 'inline',
            'Metadata' => [
                'Content-Disposition' => 'inline',
                'ContentDisposition' => 'inline'
            ],
        ]);
        $endpoint = preg_replace('#^https?://#', '', $endpoint);

        return "https://{$bucket}.{$endpoint}/{$pathName}";
    }
}
