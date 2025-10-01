<?php

namespace App\Http\Requests\Member;

use Illuminate\Support\Facades\Storage;

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

        $pathName = 'uploads/members/' . uniqid() . '.' . $image->getClientOriginalExtension();
        Storage::disk('s3')->put($pathName, fopen($image->getRealPath(), 'r+'), ['visibility' => 'public']);
        return Storage::disk('s3')->url($pathName);
    }
}
