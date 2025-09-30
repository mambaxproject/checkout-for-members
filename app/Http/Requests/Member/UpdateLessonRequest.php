<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class UpdateLessonRequest extends FormRequest
{
    use MemberRequestHelper;

    public function rules()
    {
        return [
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:5000', 'nullable'],
            'videoUrl' => ['string', 'url', function ($attribute, $value, $fail) {
                if (!$this->isValidVideoUrl($value)) {
                    $fail('A URL do vídeo deve ser do YouTube ou Vimeo.');
                }
            }],

        ];
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'type' => 'video',
            'draft' => (bool) ($this->draft === 'true'),
            'videoUrl' => $this->videoUrl,
            'videoProvider' => $this->getVideoType($this->videoUrl),
            'Attachments' => $this->getAttachments()
        ];

        return $this->removeUnchangedFields($data);
    }

    private function getAttachments(): array
    {
        if (empty($this->attachments)) {
            return [];
        }

        $attachments = [];

        foreach ($this->attachments as $attachment) {
            $attachments[] = [
                'url' => $this->saveS3($attachment),
                'type' => $attachment->getClientOriginalExtension()
            ];
        }

        return $attachments;
    }
}
