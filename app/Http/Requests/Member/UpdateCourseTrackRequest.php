<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class UpdateCourseTrackRequest extends FormRequest
{
    use MemberRequestHelper;

    public function rules()
    {
        return [
            'name' => ['string', 'max:255'],
            'thumbnail' => ['file', 'mimes:jpg,jpeg,png', 'max:5048'],
            'cover' => ['file', 'mimes:jpg,jpeg,png'],
        ];
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'categoryId' => $this->categoryId,
            'draft' => (bool) ($this->draft === 'true')
        ];

        if ($this->hasFile('thumbnail')) {
            $data['thumbnailUrl'] = $this->saveS3($this->thumbnail);
        }

        if ($this->hasFile('cover')) {
            $data['cover'] = $this->saveS3($this->cover);
        }

        
        return $this->removeUnchangedFields($data);
    }
}
