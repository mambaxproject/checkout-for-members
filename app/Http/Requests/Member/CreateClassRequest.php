<?php

namespace App\Http\Requests\Member;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }

    public function toArray(): array
    {
        $offers = $this->input('offers', []);
        $releases = [];
        $hasTrack = filter_var($this->input('hasTrack'), FILTER_VALIDATE_BOOLEAN);

        foreach ($this->all() as $key => $value) {
            if (preg_match('/^release_type_(\d+)$/', $key, $matches)) {
                $id = (int)$matches[1];
                $type = $value;

                $release = [
                    $hasTrack ? 'trackId' : 'moduleId' => $id,
                ];

                if ($type == 1) {
                    $release['type'] = 'immediate';
                } elseif ($type == 2) {
                    $release['type'] = 'days';
                    $release['days'] = (int)$this->input("release_days_$id");
                } elseif ($type == 3) {
                    $release['type'] = 'date';
                    $release['date'] = $this->input("release_date_$id");
                }

                $releases[] = $release;
            }
        }

        return [
            "name" => $this->input('name'),
            "offers" => $offers,
            "default" => $this->has('defaultClass'),
            "subscription" => $this->input('access_type') == 'private',
            "endDate" => $this->getEndDate(),
            "description" => $this->input('description'),
            "detailSubscription" => $this->getDetailDescription(),
            "Releases" => $releases,
        ];
    }

    private function getEndDate(): mixed
    {
        if ($this->input('access_type') == 'private') {
            return null;
        }

        $today = Carbon::now();
        $months = (int) $this->access_duration;
        $future = $today->addMonths($months);
        return $future->toDateString();
    }

    private function getDetailDescription(): mixed
    {
        if ($this->input('access_type') == 'private') {
            return null;
        }

        $today = Carbon::now();
        $months = (int) $this->access_duration;
        $future = $today->addMonths($months)->toDateString();
        

        return Carbon::parse($future)
            ->locale('pt_BR')
            ->translatedFormat('d/m/y');
    }
}
