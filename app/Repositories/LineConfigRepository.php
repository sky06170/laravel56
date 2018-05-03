<?php

namespace App\Repositories;

use App\Models\LineConfig;

class LineConfigRepository{

	protected $lineConfig;

	public function __construct(LineConfig $lineConfig)
	{
		$this->lineConfig = $lineConfig;
	}

	public function find()
	{
		return $this->lineConfig->find(1);
	}

	public function createAccessToken($dataArray)
	{
		return $this->lineConfig->create($dataArray);
	}

	public function updateAccessToken($dataArray)
	{
		return $this->lineConfig->update($dataArray);
	}

}

?>