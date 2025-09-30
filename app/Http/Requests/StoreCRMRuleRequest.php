<?php

namespace App\Http\Requests;

use App\Enums\{CRMEventTriggerEnum, CRMOriginEnum};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCRMRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin'        => ['required', Rule::in(array_column(CRMOriginEnum::cases(), 'value'))],
            'event_trigger' => ['required', Rule::in(array_column(CRMEventTriggerEnum::cases(), 'value'))],
            'funnel_id'     => ['required', 'string'],
            'step_id'       => ['required', 'string'],
            'funnel_name'   => ['required', 'string'],
            'step_name'     => ['required', 'string'],
        ];
    }
}
