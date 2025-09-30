<?php

namespace App\Services\ActiveCampaign\Classes;

use App\Services\ActiveCampaign\Connector;


class Tags extends Connector
{

	public function get($tag_id)
	{
		return $this->request('GET', 'tags/' . strval($tag_id));
	}

	public function all()
	{
		return $this->request(
            'GET', 'tags?limit=1000');
	}

    public function search($params)
    {
        return $this->request(
            'GET', 'tags?limit=1000&search=' . $params);
    }

	public function create($params)
	{
		return $this->request('POST', 'tags', ['tag' => $params]);
	}

	public function update($tag_id, $params)
	{
		return $this->request('PUT', 'tags/' . strval($tag_id), ['tag' => $params]);
	}

	public function delete($tag_id)
	{
		return $this->request('DELETE', 'tags/' . strval($tag_id));
	}

    public function firstOrCreate($params)
    {
        $tag = $this->search($params['tag']);

        if (isset($tag['tags']) && count($tag['tags'])) {
            return current($tag['tags']);
        }

        return $this->create($params)['tag'];
    }

}