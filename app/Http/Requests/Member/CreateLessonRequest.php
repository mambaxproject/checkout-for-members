<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class CreateLessonRequest extends FormRequest
{
    use MemberRequestHelper;

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:5000', 'nullable'],
            'videoUrl'    => ['nullable', 'string', 'url', $this->validateVideoUrl(...)],
            'videoFile'   => ['nullable', 'file', 'mimes:mp4,mov,avi,wmv,flv,webm,mkv', 'max:2097152'],
            'videoType'   => ['required', 'in:url,upload'],
        ];
    }

    public function toArray(): array
    {
        return array_merge($this->getDeafaultData(), $this->getVideoProviderData());
    }

    private function getDeafaultData(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'type' => 'video',
            'draft' => $this->draft === 'true',
            'moduleId' => $this->moduleId,
            'Attachments' => $this->getAttachments(),
        ];
    }

    private function getVideoProviderData()
    {
        if (!is_null($this->videoId)) {
            return [
                'videoProvider' => 'cloudflare',
                'videoId' => $this->videoId,
                'posterUrl' => $this->getPosterUrl()
            ];
        }

        return [
            'videoUrl' => $this->videoUrl,
            'videoProvider' => $this->getVideoType($this->videoUrl)
        ];
    }

    private function validateVideoUrl(): \Closure
    {
        return function ($attribute, $value, $fail) {
            if ($value && ! $this->isValidVideoUrl($value)) {
                $fail('A URL do vídeo deve ser do YouTube ou Vimeo.');
            }
        };
    }

    private function getPosterUrl(): mixed
    {
        if (is_null($this->coverImage)) {
            return null;
        }

        return $this->saveS3($this->coverImage);
    }

    private function getAttachments(): array
    {
        if (empty($this->attachments)) {
            return [];
        }

        return collect($this->attachments)
            ->map(fn($attachment) => [
                'url'  => $this->saveS3($attachment),
                'type' => $attachment->getClientOriginalExtension(),
            ])
            ->toArray();
    }
}
