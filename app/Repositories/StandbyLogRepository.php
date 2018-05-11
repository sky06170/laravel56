<?php

namespace App\Repositories;

use App\Models\StandbyLog;

class StandbyLogRepository{

	protected $standbyByLog;

	public function __construct(StandbyLog $standbyByLog)
	{
		$this->standbyByLog = $standbyByLog;
	}

	public function findLog($uid)
	{
		return $this->standbyByLog->where('uid', $uid)->orderBy('id', 'asc')->first();
	}

	public function log($dataArr = [])
	{
		return $this->standbyByLog->create($dataArr);
	}

	public function delete($id)
	{
		return $this->standbyByLog->destroy($id);
	}

}

?>