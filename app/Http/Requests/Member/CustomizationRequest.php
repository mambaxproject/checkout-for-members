<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Member\MemberRequestHelper;

class CustomizationRequest extends FormRequest
{
    use MemberRequestHelper;

    public function rules()
    {
        return [
            'login.firstText' => ['nullable', 'string', 'max:255'],
            'login.secondText' => ['nullable', 'string', 'max:255'],
            'login.positionBanner' => ['nullable', 'string', 'in:left,right'],
            'login.banner' => ['nullable'],
            'login.darkMode' => ['nullable'],
            'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5048'],
        ];
    }

    public function toArray(): array
    {
        $login = $this->all()['login'];
        return [
            'login' =>  [
                'logo' => $this->handleLogo(),
                'firstText' => $this->input('login.firstText'),
                'secondText' => $this->input('login.secondText'),
                'positionBanner' => $this->input('login.positionBanner'),
                'banner' => $this->handleBanner(),
                'darkMode' => array_key_exists('darkMode', $login),
            ]
        ];
    }

    protected function handleBanner(): ?string
    {
        $banner = $this->login['banner'] ?? null;

        if (is_null($banner) && !is_null($this->imgBannerOld)) {
            return $this->imgBannerOld;
        }

        if (is_null($banner)) {
            return null;
        }

        return $this->saveS3($banner);
    }

    protected function handleLogo(): ?string
    {
        $logo = $this->login['logo'] ?? null;

        if (is_null($logo) && !is_null($this->imgLogoOld)) {
            return $this->imgBannerOld;
        }

        if (is_null($logo)) {
            return null;
        }

        return $this->saveS3($logo);
    }
}
