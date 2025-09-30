<?php

namespace App\Services\ActiveCampaign;

use App\Services\ActiveCampaign\Classes\Contacts;
use App\Services\ActiveCampaign\Classes\CustomFields;
use App\Services\ActiveCampaign\Classes\Lists;
use App\Services\ActiveCampaign\Classes\Tags;

class ActiveCampaign
{
    private string $base_url;

    private string $api_key;
    private string $api_endpoint = 'api/3';

    public function __construct($base_url, $api_key)
    {
        $this->base_url = $base_url. '/' . $this->api_endpoint;
        $this->api_key = $api_key;
    }

    public function lists()
    {
        return new Lists($this->base_url, $this->api_key);
    }

    public function contacts()
    {
        return new Contacts($this->base_url, $this->api_key);
    }

    public function tags()
    {
        return new Tags($this->base_url, $this->api_key);
    }

    public function customFields()
    {
        return new CustomFields($this->base_url, $this->api_key);
    }

}