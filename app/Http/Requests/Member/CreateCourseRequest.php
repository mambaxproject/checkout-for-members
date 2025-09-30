<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class CreateCourseRequest extends FormRequest
{
    use MemberRequestHelper;
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'thumbnail' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5048'],
            'cover' => ['required', 'file', 'mimes:jpg,jpeg,png'],
        ];

        if ($this->filled('parentId')) {
            $rules['cover'] = ['nullable', 'file', 'mimes:jpg,jpeg,png'];
        }

        return $rules;
    }

    public function toArray(): array
    {
        return array_merge(
            $this->getDefaultData(),
            $this->getCourseTrack()
        );
    }

    private function getDefaultData(): array
    {
        return [
            'name' => $this->name,
            'thumbnailUrl' => $this->saveS3($this->thumbnail),
            'categoryId' => $this->categoryId,
            'description' => $this->description,
            'productRef' => $this->productUuid,
            'cover' => $this->saveS3($this->cover),
            'isTrack' => (bool) $this->isTrack
        ];
    }

    private function getCourseTrack(): array
    {
        if (is_null($this->parentId)) {
            return [];
        }

        return [
            'parentId' => $this->parentId,
            'trackId' => $this->trackId,
            'draft' => (bool) $this->inputDraft
        ];
    }
}
