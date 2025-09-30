<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class ChangeMemberModeratorRequest extends FormRequest
{
    use MemberRequestHelper;

    public function rules(): array
    {
        return [
            'moderator' => ['required', 'boolean'],
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if ((bool) $this->moderator === true) {
                        if (!\App\Models\User::where('email', $value)->exists()) {
                            $fail('O usuário precisa ser um produtor Suit Sales para ser moderador.');
                        }
                    }
                }
            ]
        ];
    }

    public function toArray(): array
    {
        return [
            'moderator' => (bool) $this->moderator,
            'email' => $this->email
        ];
    }
}
