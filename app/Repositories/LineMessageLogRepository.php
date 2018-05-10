<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LineMessageLogRepository{

	private function db()
	{
		return DB::table('line_message_log');
	}

	public function log($dataArray = [])
	{
		try{

			DB::beginTransaction();

			$response = $this->db()->insert($dataArray);

			$this->clearExcessLog();

			DB::commit();

			return $response;

		}catch(\Exception $e){

			DB::rollback();

		}
	}

	private function clearExcessLog()
	{
		$startClearAmount = 11000;

		$limitAmount = 10000;

		$totalCount = $this->db()->count();

		if($totalCount >= $startClearAmount){

			$limit = $totalCount - $limitAmount;

			$response = $this->db()->orderBy('id','asc')->offset(0)->limit($limit)->delete();

		}
	}

}

?>