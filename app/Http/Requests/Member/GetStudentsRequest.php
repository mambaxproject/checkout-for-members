<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class GetStudentsRequest extends FormRequest
{
    use MemberRequestHelper;

    public function prepareForValidation(): void
    {
        $this->merge(array_filter([
            'tab' => $this->query("tab"),
            'filters' => $this->query('filters'),
            'classId' => $this->query('classId')
        ]));
    }

    public function toArray(): array
    {
        $data = $this->getExtraStatus();
        $data['filters'] = $this->filters;
        $data['classId'] = $this->classId;
        return $data;
    }

    private function getExtraStatus(): array
    {
        if(!is_null($this->filters)){
            return [];
        }

        if (is_null($this->tab)) {
            return ['status' => 'ACTIVE'];
        }

        if ($this->tab == 'active') {
            return ['status' => 'ACTIVE'];
        }

        if ($this->tab == 'inactive') {
            return ['status' => 'INACTIVE'];
        }

        if ($this->tab == 'moderators') {
            return ['moderator' => true];
        }

        return [];
    }
}
