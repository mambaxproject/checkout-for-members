<?php

namespace App\Services\ActiveCampaign\Classes;

use App\Services\ActiveCampaign\Connector;

class CustomFields extends Connector
{

	public function all()
	{
		return $this->request('GET', 'fields?limit=100');
	}

}