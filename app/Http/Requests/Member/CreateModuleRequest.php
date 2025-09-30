<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class CreateModuleRequest extends FormRequest
{
    use MemberRequestHelper;

    public function rules()
    {
         return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:255', 'nullable'],
            'thumbnail' => [$this->checkThumbIsRequired(), 'file', 'mimes:jpg,jpeg,png', 'max:5048'],
        ];
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'thumbnailUrl' => $this->saveS3($this->thumbnail),
            'courseId' => $this->courseId,
            'draft' => (bool) ($this->draft === 'true')
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do módulo é obrigatório.',
            'description.max' => 'A descrição deve ter no máximo 245 caracteres.',
            'thumbnail.required' => 'A imagem do módulo é obrigatória.',
            'thumbnail.mimes' => 'A imagem deve ser do tipo JPG ou PNG.',
            'thumbnail.max' => 'A imagem deve ter no máximo 5MB.'
        ];
    }

    private function checkThumbIsRequired(): string
    {
        if (is_null($this->parentId)) {
            return 'required';
        }
        
        return '';
    }
}
